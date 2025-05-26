<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Сохранить новый комментарий.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'event_id' => $request->event_id,
        ]);

        return redirect()->back()->with('success', 'Комментарий добавлен');
    }

    /**
     * Удалить комментарий.
     */
    public function destroy(Comment $comment)
    {
        if (Auth::id() === $comment->user_id || Auth::user()->isAdmin()) {
            $comment->delete();
            return redirect()->back()->with('success', 'Комментарий удален');
        }

        return redirect()->back()->with('error', 'У вас нет прав на удаление этого комментария');
    }
} 