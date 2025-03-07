<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\View\View;
use App\Models\Category;
use App\Interface\Service\Post\PostServiceInterface;
use Illuminate\Http\Response;



class PostController extends Controller
{
    protected $postService;

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @param Request request
     * 
     * return view
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['category_id', 'search', 'created_at']);
        $posts = $this->postService->getPosts($filters);
        $categories = Category::all();

        return view('posts.index', compact('posts', 'categories'));
    }

    /**
     * 
     * @return View
     */
    public function create(): View
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    /**
     * Show the confirmation page after validation.
     *
     * @param Request $request
     * @return View
     */
    public function confirm(Request $request): View
    {
        $validatedData = $this->postService->validatePostData($request);

        return view('posts.confirm', [
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'category' => $validatedData['category'],
            'category_id' => $validatedData['category_id'],
        ]);
    }

    /**
     * Store the post in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->postService->storePost($request);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }
    
    /**
     * @param Post $post
     * @return View
     */
    public function edit($id)
    {
        return $this->postService->edit($id);
    }

    /**
     * Confirm the edit of a post.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function confirmEdit(Request $request, $id)
    {
        return $this->postService->confirmEdit($request, $id);
    }

    /**
     * Update an existing post.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->postService->updatePost($request, $id);
    }

    /**
     * Delete post
     * 
     * @param int $id
     * @return redirect
     */
    public function destroy($id)
    {
        return $this->postService->deletePost($id);
    }

    /**
     * @param Request $request
     * @return redirect
     */
    public function upload(Request $request)
    {
        return $this->postService->processCSVUpload($request->file('file'));
    }

    /**
     * Download all posts
     * 
     * @return Response
     */
    public function download(): Response
    {
        $csvOutput = $this->postService->generatePostsCsv();

        $filename = "posts_" . date('Y-m-d') . ".csv";

        return response($csvOutput)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Download a single post as a CSV file.
     *
     * @param int 
     * @return Response 
     */
    public function downloadSingle($id): Response
    {
        $csvOutput = $this->postService->generateSinglePostCsv($id);

        $filename = "post_{$id}_" . date('Y-m-d') . ".csv";

        return response($csvOutput)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
