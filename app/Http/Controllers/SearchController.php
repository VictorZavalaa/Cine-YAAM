<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\FavoriteList;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->query('q', ''));
        $genre = trim((string) $request->query('genre', ''));
        $year = trim((string) $request->query('year', ''));
        $sort = trim((string) $request->query('sort', 'popularity.desc'));

        $allowedSorts = [
            'popularity.desc',
            'vote_average.desc',
            'release_date.desc',
            'revenue.desc',
        ];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'popularity.desc';
        }

        $genres = $this->tmdbGenres();

        if ($query === '') {
            $discover = $this->tmdbDiscover([
                'genre' => $genre,
                'year' => $year,
                'sort' => $sort,
            ]);

            return view('search', [
                'query' => '',
                'movies' => $discover['results'],
                'error' => $discover['ok'] ? null : 'No fue posible cargar recomendaciones ahora. Intenta de nuevo.',
                'isExplore' => true,
                'genres' => $genres,
                'selectedGenre' => $genre,
                'selectedYear' => $year,
                'selectedSort' => $sort,
            ]);
        }

        $response = $this->tmdbSearch($query);

        if (! $response['ok']) {
            return view('search', [
                'query' => $query,
                'movies' => [],
                'error' => 'No fue posible consultar TMDB en este momento. Intenta de nuevo.',
                'isExplore' => false,
                'genres' => $genres,
                'selectedGenre' => $genre,
                'selectedYear' => $year,
                'selectedSort' => $sort,
            ]);
        }

        return view('search', [
            'query' => $query,
            'movies' => $response['results'],
            'error' => null,
            'isExplore' => false,
            'genres' => $genres,
            'selectedGenre' => $genre,
            'selectedYear' => $year,
            'selectedSort' => $sort,
        ]);
    }

    public function suggest(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $response = $this->tmdbSearch($query);

        if (! $response['ok']) {
            return response()->json(['suggestions' => []]);
        }

        $suggestions = collect($response['results'])
            ->take(6)
            ->map(function (array $movie) {
                return [
                    'title' => $movie['title'] ?? 'Sin título',
                    'year' => isset($movie['release_date']) && $movie['release_date'] !== ''
                        ? substr($movie['release_date'], 0, 4)
                        : null,
                ];
            })
            ->all();

        return response()->json(['suggestions' => $suggestions]);
    }

    public function detail(int $id)
    {
        $response = $this->tmdbMovieDetail($id);

        $comments = collect();
        $favoriteLists = collect();

        if (Auth::check()) {
            $favoriteLists = FavoriteList::query()
                ->where('idUserL', Auth::id())
                ->orderBy('nameL')
                ->get();
        }

        $localMovie = Movie::query()
            ->where('sourceM', 'tmdb')
            ->where('external_id', (string) $id)
            ->first();

        if ($localMovie) {
            $comments = Comment::with([
                'user',
                'reactions' => function ($query) {
                    if (Auth::check()) {
                        $query->where('idUserR', Auth::id());
                    }
                },
            ])
                ->withCount([
                    'reactions as likes_count' => fn($query) => $query->where('typeR', 'like'),
                    'reactions as dislikes_count' => fn($query) => $query->where('typeR', 'dislike'),
                ])
                ->where('idMovieC', $localMovie->id)
                ->latest()
                ->get();
        }

        if (! $response['ok']) {
            return view('detail', [
                'movie' => null,
                'error' => 'No fue posible cargar el detalle de la película en este momento.',
                'comments' => $comments,
                'favoriteLists' => $favoriteLists,
            ]);
        }

        return view('detail', [
            'movie' => $response['movie'],
            'error' => null,
            'comments' => $comments,
            'favoriteLists' => $favoriteLists,
        ]);
    }

    /**
     * @return array{ok: bool, results: array<int, array<string, mixed>>}
     */
    private function tmdbSearch(string $query): array
    {
        $token = (string) config('services.tmdb.token');

        if ($token === '') {
            return ['ok' => false, 'results' => []];
        }

        $response = Http::timeout(8)
            ->acceptJson()
            ->withToken($token)
            ->baseUrl((string) config('services.tmdb.base_url', 'https://api.themoviedb.org/3'))
            ->get('/search/movie', [
                'query' => $query,
                'language' => config('services.tmdb.language', 'es-MX'),
                'include_adult' => false,
            ]);

        if (! $response->successful()) {
            return ['ok' => false, 'results' => []];
        }

        $results = $response->json('results');

        return [
            'ok' => true,
            'results' => is_array($results) ? $results : [],
        ];
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
                'append_to_response' => 'credits,videos',
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

    /**
     * @param  array{genre: string, year: string, sort: string}  $filters
     * @return array{ok: bool, results: array<int, array<string, mixed>>}
     */
    private function tmdbDiscover(array $filters): array
    {
        $token = (string) config('services.tmdb.token');

        if ($token === '') {
            return ['ok' => false, 'results' => []];
        }

        $queryParams = [
            'language' => config('services.tmdb.language', 'es-MX'),
            'include_adult' => false,
            'sort_by' => $filters['sort'],
            'vote_count.gte' => 50,
            'with_watch_monetization_types' => 'flatrate',
        ];

        if ($filters['genre'] !== '') {
            $queryParams['with_genres'] = $filters['genre'];
        }

        if (preg_match('/^\d{4}$/', $filters['year']) === 1) {
            $queryParams['primary_release_year'] = $filters['year'];
        }

        $response = Http::timeout(8)
            ->acceptJson()
            ->withToken($token)
            ->baseUrl((string) config('services.tmdb.base_url', 'https://api.themoviedb.org/3'))
            ->get('/discover/movie', $queryParams);

        if (! $response->successful()) {
            return ['ok' => false, 'results' => []];
        }

        $results = $response->json('results');

        return [
            'ok' => true,
            'results' => is_array($results) ? array_slice($results, 0, 20) : [],
        ];
    }

    /**
     * @return array<int, array{id: int, name: string}>
     */
    private function tmdbGenres(): array
    {
        $token = (string) config('services.tmdb.token');

        if ($token === '') {
            return [];
        }

        $response = Http::timeout(8)
            ->acceptJson()
            ->withToken($token)
            ->baseUrl((string) config('services.tmdb.base_url', 'https://api.themoviedb.org/3'))
            ->get('/genre/movie/list', [
                'language' => config('services.tmdb.language', 'es-MX'),
            ]);

        if (! $response->successful()) {
            return [];
        }

        $genres = $response->json('genres');

        if (! is_array($genres)) {
            return [];
        }

        return collect($genres)
            ->filter(fn($genre): bool => is_array($genre) && isset($genre['id'], $genre['name']))
            ->map(fn(array $genre): array => [
                'id' => (int) $genre['id'],
                'name' => (string) $genre['name'],
            ])
            ->values()
            ->all();
    }
}
