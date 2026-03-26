<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'body' => 'required', // Не сохранять же пустой коммент.
        ]);

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $request['body'],
        ]);

        return redirect()->route('posts.show', $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $post = $comment->post;

        $comment->delete();

        return redirect()->route('posts.show', $post);
    }
}
