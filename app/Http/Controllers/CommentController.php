<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function createComentario(int $tmdbId): View
    {
        $response = $this->tmdbMovieDetail($tmdbId);

        if (! $response['ok']) {
            return view('movie.createComentario', [
                'movie' => null,
                'error' => 'No fue posible cargar la información de la película.',
            ]);
        }

        return view('movie.createComentario', [
            'movie' => $response['movie'],
            'error' => null,
        ]);
    }

    public function storeComentario(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tmdb_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'overview' => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
            'poster_path' => ['nullable', 'string', 'max:255'],
            'bodyComment' => ['required', 'string', 'min:3', 'max:1500'],
        ]);

        $movie = Movie::updateOrCreate(
            [
                'sourceM' => 'tmdb',
                'external_id' => (string) $validated['tmdb_id'],
            ],
            [
                'title' => $validated['title'],
                'overview' => $validated['overview'] ?? 'Sin descripción disponible.',
                'releaseDate' => $validated['release_date'] ?? null,
                'posterPath' => $validated['poster_path'] ?? null,
            ]
        );

        Comment::create([
            'idUserC' => Auth::id(),
            'idMovieC' => $movie->id,
            'bodyComment' => $validated['bodyComment'],
        ]);

        return redirect()->route('movies.detail', ['id' => $validated['tmdb_id']])
            ->with('success', 'Comentario agregado correctamente.');
    }

    public function edit(Comment $comment): View
    {
        $comment = $this->ownedComment($comment->id);

        return view('user.editComment', [
            'comment' => $comment,
            'movie' => $comment->movie,
        ]);
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $comment = $this->ownedComment($comment->id);

        $validated = $request->validate([
            'bodyComment' => ['required', 'string', 'min:3', 'max:1500'],
        ]);

        $comment->update([
            'bodyComment' => $validated['bodyComment'],
        ]);

        return redirect()->route('user.comments')
            ->with('success', 'Comentario actualizado correctamente.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment = $this->ownedComment($comment->id);

        $comment->reactions()->delete();
        $comment->notifications()->delete();
        $comment->delete();

        return redirect()->route('user.comments')
            ->with('warning', 'Comentario eliminado correctamente.');
    }

    /**
     * @return array{ok: bool, movie: array<string, mixed>|null}
     */
    private function tmdbMovieDetail(int $id): array
    {
        $token = (string) config('services.tmdb.token');

        if ($token === '') {
            return ['ok' => false, 'movie' => null];
        }

        $response = Http::timeout(8)
            ->acceptJson()
            ->withToken($token)
            ->baseUrl((string) config('services.tmdb.base_url', 'https://api.themoviedb.org/3'))
            ->get("/movie/{$id}", [
                'language' => config('services.tmdb.language', 'es-MX'),
            ]);

        if (! $response->successful()) {
            return ['ok' => false, 'movie' => null];
        }

        $movie = $response->json();

        return [
            'ok' => is_array($movie),
            'movie' => is_array($movie) ? $movie : null,
        ];
    }

    private function ownedComment(int $commentId): Comment
    {
        return Comment::with('movie')
            ->where('id', $commentId)
            ->where('idUserC', Auth::id())
            ->firstOrFail();
    }
}
