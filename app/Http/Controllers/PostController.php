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
        // Initialize the query for posts, excluding soft-deleted ones
        $query = Post::query()->whereNull('deleted_at');
    
        // Apply category filter if provided
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }
    
        // Apply search filter if provided
        if ($search = $request->input('search')) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }
    
        // Apply created_at filter if provided
        if ($createdAt = $request->input('created_at')) {
            $query->whereDate('created_at', '=', $createdAt);
        }
    
        // Apply filter by user type (if admin or regular user)
        if (Auth::user() && Auth::user()->type == 0) {
            // Admin sees all posts
            $posts = $query->orderBy('created_at', 'desc')->paginate(12);
        } else {
            // Regular user sees only posts with status = 1
            $posts = $query->where('status', 1)->orderBy('created_at', 'desc')->paginate(12);
        }
    
        // Fetch all categories for the dropdown filter
        $categories = Category::all();
    
        // Return the view with filtered posts and categories
        return view('posts.index', compact('posts', 'categories'));
    }
    

    /**
     * 
     * @return View
     */
    public function create()
    {
        $categories = Category::all();  // Fetch all categories
        return view('posts.create', compact('categories'));  // Pass the categories to the view
    }

    /**
     * @param Request $request
     * @return View
     */
   public function confirm(Request $request)
{
    // Validate title, description, and category_id
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,id',  // Validate that category exists
    ]);

    // Fetch the category based on the validated category_id
    $category = Category::find($validatedData['category_id']);

    // Pass the validated data and category to the view
    return view('posts.confirm', [
        'title' => $validatedData['title'],
        'description' => $validatedData['description'],
        'category' => $category,  // Pass category object
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

    // Check authorization
    if (optional(Auth::user())->id !== $post->create_user_id && optional(Auth::user())->type !== 0) {
        return redirect()->route('posts.index')->with('error', 'You are not authorized to edit this post.');
    }

    // Fetch all categories for the dropdown
    $categories = Category::all();

    // Pass the post and categories to the view
    return view('posts.edit', compact('post', 'categories'));
}

    /**
     * @param request $request
     * @return View
     */
    public function confirmEdit(Request $request, $id)
{
    // Validate the request data
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,id', // Ensure category_id is validated
    ]);
    
    // Find the post by ID
    $post = Post::findOrFail($id);

    // Fetch the category by ID
    $category = Category::find($request->input('category_id'));
    
    // If the category exists, get its name
    $categoryName = $category ? $category->name : 'No category assigned';

    // Pass data to the confirm view
    return view('posts.confirmedit', [
        'post' => $post,
        'title' => $validatedData['title'],
        'description' => $validatedData['description'],
        'status' => $request->has('status') ? 1 : 0,
        'category_id' => $category ? $category->id : null, // Pass category_id
        'category' => $categoryName, // Pass category name for display
    ]);
}

    

    /**
     * @param Request $request
     * @param int $id 
     * @return redirect
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ]);
    
        // Find the post by ID
        $post = Post::findOrFail($id);
    
        // Set the updated_user_id to the current authenticated user
        $post->updated_user_id = Auth::id();
    
        // Update the post details
        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ? 1 : 0,
            'category_id' => $request->category_id, // Update the category_id
        ]);
    
        // After update, redirect with success message
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
        $data = array_map('str_getcsv', file($path));
    
        $header = $data[0];
        if (count($header) !== 4) { // Expecting 4 columns: title, description, status, and category_name
            return redirect()->back()->with('error', 'The CSV file must have 4 columns: title, description, status, and category_name.');
        }
        unset($data[0]);
    
        // Loop through CSV rows and insert data
        foreach ($data as $row) {
            if (count($row) !== 4) {
                return redirect()->back()->with('error', 'Each row in the CSV must have exactly 4 columns.');
            }
    
            // Find the category_id based on category_name, or create a new category if not found
            $category = Category::where('name', $row[3])->first(); // Searching for category by name
    
            if (!$category) {
                // If the category doesn't exist, create a new category
                $category = Category::create([
                    'name' => $row[3],
                ]);
            }
    
            // Prepare the data to be inserted
            $insertData[] = [
                'title' => $row[0],
                'description' => $row[1],
                'status' => (int) $row[2],
                'category_id' => $category->id, // Use category_id mapped from category_name
                'create_user_id' => Auth::id(),
                'updated_user_id' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    
        // Insert data into the posts table
        if (!empty($insertData)) {
            Post::insert($insertData);
        }
    
        return redirect()->back()->with('success', 'Posts uploaded successfully!');
    }
    
        /**
         * 
         * @return Response
         */
        public function download()
        {
            $posts = Post::with('category')->get(); // Eager load categories to avoid extra queries
        
            $csvHeader = ['ID', 'Title', 'Description', 'Status', 'Category', 'Created User ID', 'Updated User ID', 'Deleted User ID', 'Deleted At', 'Created At', 'Updated At'];
            $csvData = [];
        
            foreach ($posts as $post) {
                $csvData[] = [
                    $post->id,
                    $post->title,
                    $post->description,
                    $post->status,
                    $post->category ? $post->category->name : 'No Category', // Fetch category name
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
        
    public function downloadSingle($id)
    {
        $post = Post::with('category')->findOrFail($id); // Fetch the post along with category
    
        // Define the CSV header
        $csvHeader = ['ID', 'Title', 'Description', 'Status', 'Category', 'Created User ID', 'Updated User ID', 'Deleted User ID', 'Deleted At', 'Created At', 'Updated At'];
    
        // Add the post data to an array for the CSV
        $csvData = [
            $post->id,
            $post->title,
            $post->description,
            $post->status,
            $post->category ? $post->category->name : 'No Category', // Fetch category name instead of ID
            $post->create_user_id,
            $post->updated_user_id,
            $post->deleted_user_id ?? '',
            $post->deleted_at ?? '',
            $post->created_at,
            $post->updated_at,
        ];
    
        $filename = "post_{$post->id}_" . date('Y-m-d') . ".csv";
    
        // Open output stream
        $handle = fopen('php://output', 'w');
        ob_start();
    
        // Add the CSV header and the post data
        fputcsv($handle, $csvHeader);
        fputcsv($handle, $csvData);
    
        fclose($handle);
    
        $csvOutput = ob_get_clean();
    
        return response($csvOutput)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
}
