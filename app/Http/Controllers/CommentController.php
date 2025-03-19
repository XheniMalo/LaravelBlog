<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Services\CommentService;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(CommentRequest $request)
    {
        $validated = $request->validated();

        $comment = $this->commentService->createComment($validated);

        return redirect()->back()->with('success', 'Comment added successfully');
    }

    public function reply(CommentRequest $request)
    {
        $validated = $request->validated();

        $reply = $this->commentService->createReply($validated);

        return redirect()->back()->with('success', 'Reply added successfully');
    }

    public function update(Comment $comment, CommentRequest $request)
    {

        $this->commentService->updateComment($comment, $request->validated());

        return redirect()->back()->with('success', 'Comment updated successfully');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully');
    }

}