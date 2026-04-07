<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\FavoriteList;
use App\Models\FavoriteListItem;
use App\Models\Movie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        $this->migrateLegacyFavoritesIfNeeded();

        $lists = FavoriteList::query()
            ->with(['items.movie'])
            ->withCount('items')
            ->where('idUserL', Auth::id())
            ->latest()
            ->get();

        return view('favorites.index', [
            'lists' => $lists,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tmdb_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'overview' => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
            'poster_path' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:20'],
            'list_id' => ['nullable', 'integer'],
            'new_list_name' => ['nullable', 'string', 'max:100'],
        ]);

        $movie = Movie::updateOrCreate(
            [
                'sourceM' => $validated['source'] ?? 'tmdb',
                'external_id' => (string) $validated['tmdb_id'],
            ],
            [
                'title' => $validated['title'],
                'overview' => $validated['overview'] ?? 'Sin descripción disponible.',
                'releaseDate' => $validated['release_date'] ?? null,
                'posterPath' => $validated['poster_path'] ?? null,
            ]
        );

        $list = $this->resolveTargetList($validated);

        FavoriteListItem::firstOrCreate([
            'idListLI' => $list->id,
            'idMovieLI' => $movie->id,
        ]);

        return back()->with('success', "Película guardada en la lista '{$list->nameL}'.");
    }

    public function show(int $id): View|RedirectResponse
    {
        $list = FavoriteList::query()
            ->where('id', $id)
            ->where('idUserL', Auth::id())
            ->first();

        if (! $list) {
            return redirect()->route('favorites.index')->with('error', 'No se encontró esa lista.');
        }

        $list->load([
            'items' => fn($query) => $query->latest()->with('movie'),
        ]);

        return view('favorites.show', [
            'list' => $list,
            'items' => $list->items,
        ]);
    }

    public function storeList(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nameL' => ['required', 'string', 'max:100'],
        ]);

        FavoriteList::firstOrCreate([
            'idUserL' => Auth::id(),
            'nameL' => trim($validated['nameL']),
        ]);

        return back()->with('success', 'Lista creada correctamente.');
    }

    public function updateList(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'nameL' => ['required', 'string', 'max:100'],
        ]);

        $list = FavoriteList::query()
            ->where('id', $id)
            ->where('idUserL', Auth::id())
            ->first();

        if (! $list) {
            return back()->with('error', 'No se encontró la lista que intentas editar.');
        }

        $newName = trim($validated['nameL']);

        $exists = FavoriteList::query()
            ->where('idUserL', Auth::id())
            ->where('nameL', $newName)
            ->where('id', '!=', $list->id)
            ->exists();

        if ($exists) {
            return back()->with('warning', 'Ya tienes una lista con ese nombre.');
        }

        $list->update(['nameL' => $newName]);

        return back()->with('success', 'Lista actualizada correctamente.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $list = FavoriteList::query()
            ->where('id', $id)
            ->where('idUserL', Auth::id())
            ->first();

        if (! $list) {
            return back()->with('error', 'No se encontró esa lista en tu cuenta.');
        }

        FavoriteListItem::query()->where('idListLI', $list->id)->delete();
        $list->delete();

        return redirect()->route('favorites.index')->with('success', 'Lista eliminada correctamente.');
    }

    public function removeMovie(int $listId, int $itemId): RedirectResponse
    {
        $list = FavoriteList::query()
            ->where('id', $listId)
            ->where('idUserL', Auth::id())
            ->first();

        if (! $list) {
            return redirect()->route('favorites.index')->with('error', 'No se encontró la lista seleccionada.');
        }

        $deleted = FavoriteListItem::query()
            ->where('id', $itemId)
            ->where('idListLI', $list->id)
            ->delete();

        if ($deleted === 0) {
            return back()->with('warning', 'No se encontró esa película en la lista.');
        }

        return back()->with('success', 'Película eliminada de la lista.');
    }

    private function resolveTargetList(array $validated): FavoriteList
    {
        $listId = isset($validated['list_id']) ? (int) $validated['list_id'] : null;
        $newListName = trim((string) ($validated['new_list_name'] ?? ''));

        if ($listId) {
            $list = FavoriteList::query()
                ->where('id', $listId)
                ->where('idUserL', Auth::id())
                ->first();

            if ($list) {
                return $list;
            }
        }

        if ($newListName !== '') {
            return FavoriteList::firstOrCreate([
                'idUserL' => Auth::id(),
                'nameL' => $newListName,
            ]);
        }

        $default = FavoriteList::query()
            ->where('idUserL', Auth::id())
            ->orderBy('id')
            ->first();

        if ($default) {
            return $default;
        }

        return FavoriteList::create([
            'idUserL' => Auth::id(),
            'nameL' => 'Ver más tarde',
        ]);
    }

    private function migrateLegacyFavoritesIfNeeded(): void
    {
        $userId = (int) Auth::id();

        if ($userId <= 0) {
            return;
        }

        $hasLists = FavoriteList::query()->where('idUserL', $userId)->exists();
        if ($hasLists) {
            return;
        }

        $legacyFavorites = Favorite::query()
            ->where('idUserF', $userId)
            ->orderBy('id')
            ->get();

        if ($legacyFavorites->isEmpty()) {
            return;
        }

        $defaultList = FavoriteList::create([
            'idUserL' => $userId,
            'nameL' => 'Mis favoritos',
        ]);

        foreach ($legacyFavorites as $legacyFavorite) {
            FavoriteListItem::firstOrCreate([
                'idListLI' => $defaultList->id,
                'idMovieLI' => $legacyFavorite->idMovieF,
            ]);
        }
    }
}
