<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query()->whereNull("deleted_at"); // Start with a base query for posts

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
        info($request->all()); 
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
            'updated_user_id' => Auth::id(), // Add this line to set updated_user_id
        ]);

        // Redirect back to the post list with a success message
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }
    public function edit($id)
    {
        $post = Post::findOrFail($id);
    
        // Check if the user is authorized (creator of the post or admin)
        if (optional(Auth::user())->id !== $post->create_user_id && optional(Auth::user())->type !== 0) {
            return redirect()->route('posts.index')->with('error', 'You are not authorized to edit this post.');
        }
    
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
        $post = Post::findOrFail($id);
    
        // Ensure only the post owner or admin can delete
        if (optional(Auth::user())->id !== $post->create_user_id && optional(Auth::user())->type !== 0) {
            return redirect()->route('posts.index')->with('error', 'You are not authorized to delete this post.');
        }
    
        DB::transaction(function () use ($post) {
            $post->update(['deleted_user_id' => Auth::id()]);
            $post->delete();
        });
    
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    
    public function upload(Request $request)
    {
        // Step 1: Validate the File
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:2048', // Only allow CSV or TXT files under 2MB
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Step 2: Read the File
        $file = $request->file('file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path)); // Read and convert CSV data into an array
    
        // Step 3: Validate the CSV Header
        $header = $data[0]; // First row of the CSV is the header
        if (count($header) !== 3) {
            return redirect()->back()->with('error', 'The CSV file must have 3 columns: title, description, and status.');
        }
    
        // Remove the header row
        unset($data[0]);
    
        // Step 4: Insert or Update Data
        foreach ($data as $row) {
            if (count($row) !== 3) {
                return redirect()->back()->with('error', 'Each row in the CSV must have exactly 3 columns.');
            }
    
            // Prepare the data for insertion
            $insertData[] = [
                'title' => $row[0],                     // Hero title from the CSV
                'description' => $row[1],               // Hero voice line from the CSV
                'status' => (int) $row[2],              // Status (1 for active, etc.)
                'create_user_id' => Auth::id(),         // Automatically set the current authenticated user's ID
                'updated_user_id' => Auth::id(),        // Set the same user for updates
                'created_at' => now(),                  // Automatically set the current timestamp
                'updated_at' => now(),                  // Automatically set the current timestamp
            ];
        }
    
        // Step 5: Insert Data into the Database
        if (!empty($insertData)) {
            Post::insert($insertData); // Bulk insert into the database
        }
    
        // Step 6: Redirect with Success Message
        return redirect()->back()->with('success', 'Posts uploaded successfully!');
    }
    public function download()
    {
        $posts = Post::get(); // Fetches deleted posts too
    
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
                $post->deleted_user_id ?? '', // Show 'N/A' if null
                $post->deleted_at ?? '',   // Show 'Active' if not deleted
                $post->created_at,
                $post->updated_at,
            ];
        }
    
        $filename = "posts_" . date('Y-m-d') . ".csv";
        
        // Generate and return CSV file
        $handle = fopen('php://output', 'w');
        ob_start();
        
        // Add CSV headers
        fputcsv($handle, $csvHeader);
        
        // Add data
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        
        fclose($handle);
        
        $csvOutput = ob_get_clean();
        
        return response($csvOutput)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
    
}
