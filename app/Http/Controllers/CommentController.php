<?php

namespace App\Http\Controllers;

use App\Interface\Service\Comment\CommentServiceInterface;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentServiceInterface $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * @param Request $request
     * @param int $postId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $postId)
    {
        $validatedData = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $this->commentService->storeComment($postId, $validatedData);

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if (!$this->commentService->deleteComment($id)) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }
}
