<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentNotification;
use App\Models\CommentReaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentReactionController extends Controller
{
    public function react(Request $request, Comment $comment): RedirectResponse
    {
        $validated = $request->validate([
            'reaction' => ['required', 'in:like,dislike'],
            'tmdb_id' => ['required', 'integer'],
        ]);

        $userId = (int) Auth::id();

        if ($comment->idUserC === $userId) {
            return redirect()->route('movies.detail', ['id' => $validated['tmdb_id']])
                ->with('warning', 'No puedes reaccionar a tu propio comentario.');
        }

        CommentReaction::updateOrCreate(
            [
                'idCommentR' => $comment->id,
                'idUserR' => $userId,
            ],
            [
                'typeR' => $validated['reaction'],
            ]
        );

        $actorName = Auth::user()?->name ?? 'Alguien';

        CommentNotification::updateOrCreate(
            [
                'idUserN' => $comment->idUserC,
                'idFromUserN' => $userId,
                'idCommentN' => $comment->id,
            ],
            [
                'typeN' => $validated['reaction'],
                'messageN' => $validated['reaction'] === 'like'
                    ? "{$actorName} le dio like a tu comentario."
                    : "{$actorName} le dio dislike a tu comentario.",
                'isReadN' => false,
            ]
        );

        return redirect()->route('movies.detail', ['id' => $validated['tmdb_id']])
            ->with('success', 'Reacción registrada correctamente.');
    }
}
