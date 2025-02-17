<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Initialize the query to exclude soft-deleted posts
        $query = Post::query()->whereNull("deleted_at");
    
        // Apply search filter
        if ($search = $request->input('search')) {
            $query->where(function($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%')
                      ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }
        if ($createdAt = $request->input('created_at')) {
            $query->whereDate('created_at', '=', $createdAt);
        }
    
        // Apply additional conditions based on user type
        if (Auth::user() && Auth::user()->type == 0) {
            // Admins can view all posts
            $posts = $query->orderBy('created_at', 'desc')->paginate(10);
        } else {
            // Regular users can only view active posts
            $posts = $query->where('status', 1)->orderBy('created_at', 'desc')->paginate(10);
        }
    
        // Return the view with paginated posts
        return view('posts.index', compact('posts'));
    }
    
    /**
     * 
     * @return View
     */
    public function create()
    {

        return view('posts.create');
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
        ]);
        return view('posts.confirm', [
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
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
        ]);

        Post::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
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

        return view('posts.edit', compact('post'));
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
        ]);
        $post = Post::findOrFail($id);
        info($request);
        return view('posts.confirmedit', [
            'post' => $post,
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'status' => $request->has('status') ? 1 : 0,
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
        ]);

        $post = Post::findOrFail($id);

        $isEdited = $post->title !== $request->title || $post->description !== $request->description;

        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ? 1 : 0,
            'updated_user_id' => $isEdited ? auth()->id() : null,
            'updated_at' => $isEdited ? now() : $post->updated_at,
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
        $data = array_map('str_getcsv', file($path));

        $header = $data[0];
        if (count($header) !== 3) {
            return redirect()->back()->with('error', 'The CSV file must have 3 columns: title, description, and status.');
        }
        unset($data[0]);

        foreach ($data as $row) {
            if (count($row) !== 3) {
                return redirect()->back()->with('error', 'Each row in the CSV must have exactly 3 columns.');
            }

            $insertData[] = [
                'title' => $row[0],
                'description' => $row[1],
                'status' => (int) $row[2],
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
     * @return Response
     */
    public function download()
    {
        $posts = Post::get();

        $csvHeader = ['ID', 'Title', 'Description', 'Status', 'Created User ID', 'Updated User ID', 'Deleted User ID', 'Deleted At', 'Created At', 'Updated At'];
        $csvData = [];

        foreach ($posts as $post) {
            $csvData[] = [
                $post->id,
                $post->title,
                $post->description,
                $post->status,
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
    $post = Post::findOrFail($id); // Find the post by its ID

    // Define the CSV header
    $csvHeader = ['ID', 'Title', 'Description', 'Status', 'Created User ID', 'Updated User ID', 'Deleted User ID', 'Deleted At', 'Created At', 'Updated At'];

    // Add the post data to an array for the CSV
    $csvData = [
        $post->id,
        $post->title,
        $post->description,
        $post->status,
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
