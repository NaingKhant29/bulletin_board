<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Category;

class PostController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // dd(request()->cookie());
        $query = Post::query()->whereNull('deleted_at');
    
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }
    
        if ($createdAt = $request->input('created_at')) {
            $query->whereDate('created_at', '=', $createdAt);
        }

        if (Auth::user() && Auth::user()->type == 0) {
            $posts = $query->orderBy('created_at', 'desc')->paginate(12);
        } else {
            $posts = $query->where('status', 1)->orderBy('created_at', 'desc')->paginate(12);
        }
    
        $posts->appends(request()->query());
    
        $categories = Category::all();
    
        return view('posts.index', compact('posts', 'categories'));
    }
    
    /**
     * 
     * @return View
     */
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    /**
     * @param Request $request
     * @return View
     */
    public function confirm(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);
        $category = Category::find($validatedData['category_id']);

        return view('posts.confirm', [
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'category' => $category,
            'category_id' => $validatedData['category_id'],
        ]);
    }

    /**
     * @param Request $request
     * @return redirect
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);
    
        // Check if a post with the same title already exists
        if (Post::where('title', $request->input('title'))->exists()) {
            return redirect()->route('posts.index')->with('error', 'A post with this title already exists.');
        }
    
        Post::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'create_user_id' => Auth::id(),
            'updated_user_id' => Auth::id(),
        ]);
    
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }
    

    /**
     * @param Post $post
     * @return View
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        if (optional(Auth::user())->id !== $post->create_user_id && optional(Auth::user())->type !== 0) {
            return redirect()->route('posts.index')->with('error', 'You are not authorized to edit this post.');
        }

        $categories = Category::all();

        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * @param request $request
     * @return View
     */
    public function confirmEdit(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = Post::findOrFail($id);

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
     * @param Request $request
     * @param int $id 
     * @return redirect
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = Post::findOrFail($id);
        $post->updated_user_id = Auth::id();
        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ? 1 : 0,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully');
    }

    /**
     * @param int $id
     * @return redirect
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if (optional(Auth::user())->id !== $post->create_user_id && optional(Auth::user())->type !== 0) {
            return redirect()->route('posts.index')->with('error', 'You are not authorized to delete this post.');
        }
        DB::transaction(function () use ($post) {
            $post->update(['deleted_user_id' => Auth::id()]);
            $post->delete();
        });

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    /**
     * @param Request $request
     * @return redirect
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $file = $request->file('file');
        $path = $file->getRealPath();
    
        $handle = fopen($path, 'r');
        $delimiter = $this->detectDelimiter($path);
        $data = [];
    
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $data[] = $row;
        }
        fclose($handle);
    
        if (empty($data)) {
            return redirect()->back()->with('error', 'CSV file is empty or invalid.');
        }
    
        $header = array_map('trim', $data[0]);
        $expectedColumns = ['title', 'description', 'status', 'category_name'];
    
        if (array_diff($expectedColumns, $header)) {
            return redirect()->back()->with('error', 'CSV file must contain: title, description, status, category_name.');
        }
    
        unset($data[0]);
    
        $insertData = [];
        $existingTitles = Post::pluck('title')->toArray(); // Fetch existing titles
    
        foreach ($data as $row) {
            $rowAssoc = array_combine($header, $row);
    
            if (!$rowAssoc) {
                continue;
            }
    
            if (empty($rowAssoc['category_name'])) {
                return redirect()->back()->with('error', 'Each row must have a category name.');
            }
    
            if (in_array($rowAssoc['title'], $existingTitles)) {
                return redirect()->back()->with('error', "The title '{$rowAssoc['title']}' already exists.");
            }
    
            $category = Category::firstOrCreate(['name' => $rowAssoc['category_name']]);
    
            $insertData[] = [
                'title' => $rowAssoc['title'],
                'description' => $rowAssoc['description'],
                'status' => (int) $rowAssoc['status'],
                'category_id' => $category->id,
                'create_user_id' => Auth::id(),
                'updated_user_id' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    
        if (!empty($insertData)) {
            Post::insert($insertData);
        }
    
        return redirect()->back()->with('success', 'Posts uploaded successfully!');
    }
    
    
    /**
     * 
     * @return string
     */
    private function detectDelimiter($filePath)
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
     * 
     * @return Response
     */
    public function download()
    {
        $posts = Post::with('category')->get();

        $csvHeader = ['ID', 'Title', 'Description', 'Status', 'Category', 'Created User ID', 'Updated User ID', 'Deleted User ID', 'Deleted At', 'Created At', 'Updated At'];
        $csvData = [];

        foreach ($posts as $post) {
            $csvData[] = [
                $post->id,
                $post->title,
                $post->description,
                $post->status,
                $post->category ? $post->category->name : 'No Category',
                $post->create_user_id,
                $post->updated_user_id,
                $post->deleted_user_id ?? '',
                $post->deleted_at ?? '',
                $post->created_at,
                $post->updated_at,
            ];
        }

        $filename = "posts_" . date('Y-m-d') . ".csv";

        $handle = fopen('php://output', 'w');
        ob_start();

        fputcsv($handle, $csvHeader);

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        $csvOutput = ob_get_clean();

        return response($csvOutput)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    /**
     * Download a single post as a CSV file.
     *
     * @param int $id The ID of the post to download.
     * @return \Illuminate\Http\Response The CSV file as a downloadable response.
     */
    public function downloadSingle($id)
    {
        $post = Post::with('category')->findOrFail($id);

        $csvHeader = ['ID', 'Title', 'Description', 'Status', 'Category', 'Created User ID', 'Updated User ID', 'Deleted User ID', 'Deleted At', 'Created At', 'Updated At'];

        $csvData = [
            $post->id,
            $post->title,
            $post->description,
            $post->status,
            $post->category ? $post->category->name : 'No Category',
            $post->create_user_id,
            $post->updated_user_id,
            $post->deleted_user_id ?? '',
            $post->deleted_at ?? '',
            $post->created_at,
            $post->updated_at,
        ];

        $filename = "post_{$post->id}_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');
        ob_start();
        fputcsv($handle, $csvHeader);
        fputcsv($handle, $csvData);
        fclose($handle);

        $csvOutput = ob_get_clean();

        return response($csvOutput)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
