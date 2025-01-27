<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Post;

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

        // Paginate results, showing 10 posts per page
        $posts = $query->paginate(10);

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
            'description' => 'nullable|string',    // Description is optional
        ]);

        // Create a new post
        Post::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        // Redirect back to the post list with a success message
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }
}
