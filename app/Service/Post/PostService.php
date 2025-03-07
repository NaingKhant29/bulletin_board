<?php

namespace App\Service\Post;

use App\Interface\Service\Post\PostServiceInterface;
use App\Interface\Dao\Post\PostDaoInterface;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostService implements PostServiceInterface
{
    protected $postDao;

    public function __construct(PostDaoInterface $postDao)
    {
        $this->postDao = $postDao;
    }

    /**
     * Get filtered posts based on given filters.
     *
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPosts(array $filters)
    {
        return $this->postDao->getFilteredPosts($filters);
    }

    /**
     * Validate and retrieve post data from the request.
     *
     * @param Request $request
     * @return array
     */
    public function validatePostData(Request $request): array
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $validatedData['category'] = Category::find($validatedData['category_id']);

        return $validatedData;
    }

    /**
     * Store post data into the database.
     *
     * @param Request $request
     * @return void
     */
    public function storePost(Request $request): void
    {
        $validatedData = $this->validatePostData($request);

        $this->postDao->createPost($validatedData);
    }

    /**
     * Show the post edit form.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $post = $this->postDao->findPostById($id);

        if (optional(Auth::user())->id !== $post->created_user_id && optional(Auth::user())->type !== 0) {
            return redirect()->route('posts.index')->with('error', 'You are not authorized to edit this post.');
        }

        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Confirm the post edit.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function confirmEdit(Request $request, $id): \Illuminate\View\View
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = $this->postDao->findPostById($id);
        $category = Category::find($request->input('category_id'));
        $categoryName = $category ? $category->name : 'No category assigned';

        return view('posts.confirmedit', [
            'post' => $post,
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'status' => $request->has('status') ? 1 : 0,
            'category_id' => $category ? $category->id : null,
            'category' => $categoryName,
        ]);
    }

    /**
     * Update the post data.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePost(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        $validatedData['updated_user_id'] = Auth::id();
        $this->postDao->updatePost($id, $validatedData);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully');
    }

    /**
     * Delete the post.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePost($id): \Illuminate\Http\RedirectResponse
    {
        $post = $this->postDao->findPostById($id);

        if (optional(Auth::user())->id !== $post->created_user_id && optional(Auth::user())->type !== 0) {
            return redirect()->route('posts.index')->with('error', 'You are not authorized to delete this post.');
        }

        $this->postDao->deletePost($id);

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    /**
     * Process the CSV file upload.
     *
     * @param mixed $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processCSVUpload($file): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make(['file' => $file], [
            'file' => 'required|mimes:csv,txt|max:5120',
        ]);

        if ($validator->fails()) {
            dd($validator->errors());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $path = $file->getRealPath();
        $handle = fopen($path, 'r');
        $delimiter = $this->detectDelimiter($path);

        if (!$handle) {
            return redirect()->back()->with('error', 'Cannot open the file.');
        }

        $header = fgetcsv($handle, 1000, $delimiter);
        if (!$header) {
            return redirect()->back()->with('error', 'Invalid CSV file.');
        }

        $expectedColumns = ['title', 'description', 'status', 'category_name'];
        if (array_diff($expectedColumns, $header)) {
            return redirect()->back()->with('error', 'CSV file must contain: title, description, status, category_name.');
        }

        $existingTitles = Post::pluck('title')->toArray();
        $postsData = [];

        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $rowAssoc = array_combine($header, $row);
            if (!$rowAssoc || empty($rowAssoc['title']) || empty($rowAssoc['category_name'])) {
                continue;
            }

            if (in_array($rowAssoc['title'], $existingTitles)) {
                $duplicateTitles[] = $rowAssoc['title']; // Store duplicate titles
                continue;
            }

            $category = Category::firstOrCreate(['name' => $rowAssoc['category_name']]);

            $postsData[] = [
                'title' => $rowAssoc['title'],
                'description' => $rowAssoc['description'],
                'status' => (int) $rowAssoc['status'],
                'category_id' => $category->id,
                'created_user_id' => Auth::id(),
                'updated_user_id' => Auth::id(),
            ];
        }

        fclose($handle);
        if (!empty($duplicateTitles)) {
            return redirect()->back()->with('error', 'The following titles already exist: ' . implode(', ', $duplicateTitles));
        }
        if (!empty($postsData)) {
            $this->postDao->uploadPostsFromCSV($postsData);
            return redirect()->route('posts.index')->with('success', 'CSV uploaded successfully!');
        }
        return redirect()->back()->with('error', 'No valid data found to upload.');
    }

    /**
     * Detect the delimiter of the CSV file.
     *
     * @param string $filePath
     * @return string
     */
    private function detectDelimiter($filePath): string
    {
        $delimiters = [',', "\t", ';'];
        $handle = fopen($filePath, 'r');
        $line = fgets($handle);
        fclose($handle);

        foreach ($delimiters as $delimiter) {
            if (substr_count($line, $delimiter) > 0) {
                return $delimiter;
            }
        }
        return ',';
    }

    /**
     * Generate CSV for all posts.
     *
     * @return string
     */
    public function generatePostsCsv(): string
    {
        $posts = $this->postDao->getAllPosts();

        $csvHeader = ['ID', 'Title', 'Description', 'Status', 'Category', 'Created User ID', 'Updated User ID', 'Deleted User ID', 'Deleted At', 'Created At', 'Updated At'];

        $csvData = [];

        foreach ($posts as $post) {
            $csvData[] = [
                $post->id,
                $post->title,
                $post->description,
                $post->status,
                $post->category ? $post->category->name : 'No Category',
                $post->created_user_id,
                $post->updated_user_id,
                $post->deleted_user_id ?? '',
                $post->deleted_at ?? '',
                $post->created_at,
                $post->updated_at,
            ];
        }

        $handle = fopen('php://output', 'w');
        ob_start();

        fputcsv($handle, $csvHeader);

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        return ob_get_clean();
    }

    /**
     * Generate CSV for a single post.
     *
     * @param int $id
     * @return string
     */
    public function generateSinglePostCsv($id): string
    {
        $post = $this->postDao->findPostById($id);

        $csvHeader = ['ID', 'Title', 'Description', 'Status', 'Category', 'Created User ID', 'Updated User ID', 'Deleted User ID', 'Deleted At', 'Created At', 'Updated At'];

        $csvData = [
            $post->id,
            $post->title,
            $post->description,
            $post->status,
            $post->category ? $post->category->name : 'No Category',
            $post->created_user_id,
            $post->updated_user_id,
            $post->deleted_user_id ?? '',
            $post->deleted_at ?? '',
            $post->created_at,
            $post->updated_at,
        ];

        $handle = fopen('php://output', 'w');
        ob_start();

        fputcsv($handle, $csvHeader);
        fputcsv($handle, $csvData);

        fclose($handle);
        return ob_get_clean();
    }
}
