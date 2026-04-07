<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentNotification;
use App\Models\FavoriteList;
use App\Models\FavoriteListItem;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $favorites = FavoriteListItem::with('movie')
            ->whereHas('list', fn($query) => $query->where('idUserL', $user->id))
            ->latest()
            ->take(6)
            ->get();

        $comments = Comment::with('movie')
            ->where('idUserC', $user->id)
            ->latest()
            ->take(4)
            ->get();

        return view('user.profile', [
            'user' => $user,
            'favorites' => $favorites,
            'comments' => $comments,
            'favoritesCount' => FavoriteListItem::query()
                ->whereHas('list', fn($query) => $query->where('idUserL', $user->id))
                ->count(),
            'commentsCount' => Comment::where('idUserC', $user->id)->count(),
            'unreadNotificationsCount' => CommentNotification::where('idUserN', $user->id)
                ->where('isReadN', false)
                ->count(),
        ]);
    }

    public function notifications(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $notifications = CommentNotification::with(['fromUser', 'comment.movie'])
            ->where('idUserN', $user->id)
            ->latest()
            ->get();

        CommentNotification::where('idUserN', $user->id)
            ->where('isReadN', false)
            ->update([
                'isReadN' => true,
                'updated_at' => now(),
            ]);

        return view('user.notifications', [
            'user' => $user,
            'notifications' => $notifications,
        ]);
    }

    public function favorites(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $lists = FavoriteList::query()
            ->with(['items.movie'])
            ->withCount('items')
            ->where('idUserL', $user->id)
            ->latest()
            ->get();

        return view('user.favorites', [
            'user' => $user,
            'lists' => $lists,
        ]);
    }

    public function comments(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $comments = Comment::with('movie')
            ->where('idUserC', $user->id)
            ->latest()
            ->get();

        return view('user.comments', [
            'user' => $user,
            'comments' => $comments,
        ]);
    }

    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();

        return view('user.editProfile', [
            'user' => $user,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.show')->with('status', 'Perfil actualizado correctamente.');
    }
}
