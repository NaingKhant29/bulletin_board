<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query(); // Start with a base query for posts

        // Apply search filter if a keyword is provided
        if ($search = $request->input('search')) {
            $query->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%');
        }
        if (Auth::user() && Auth::user()->type == 0) { // Assuming type 0 is admin
            $posts = $query->paginate(10); // Show all posts, including inactive ones
        } else {
            // For regular users (type = 1), only show active posts (status = 1)
            $posts = $query->where('status', 1)->paginate(10); // Only active posts
        }



        // Return the view with posts data
        return view('posts.index', compact('posts'));
    }

    // Show confirmation page before creating a post
    public function create()
    {
        return view('posts.create'); // This view will show a form for creating a post
    }

    // Show confirmation screen before post creation
    public function confirm(Request $request)
    {
        // Pass the input values to the confirm view
        return view('posts.confirm', [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);
    }


    // Store the new post in the database
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'title' => 'required|string|max:255',  // Title must not be empty
            'description' => 'required|string',    // Description is optional
        ]);

        // Create a new post
        Post::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'create_user_id' => Auth::id(),
        ]);

        // Redirect back to the post list with a success message
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    public function confirmEdit(Request $request, $id)
    {
        // Retrieve the post from the database
        $post = Post::findOrFail($id);
        // Log::info($request);
        info($request);

        // Pass the edited values to the confirmation view
        return view('posts.confirmedit', [
            'post' => $post,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->has('status') ? 1 : 0,
        ]);
    }


    public function update(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|boolean',
        ]);

        // Find the post to update
        $post = Post::findOrFail($id);

        // Update the post
        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ? 1 : 0, // Convert status to 1 or 0
        ]);

        // Redirect to the posts index page with a success message
        return redirect()->route('posts.index')->with('success', 'Post updated successfully');
    }

    public function destroy($id)
    {
        // Find the post by ID
        $post = Post::findOrFail($id);

        // Soft delete the post
        $post->delete();

        // Redirect back with a success message
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

}
