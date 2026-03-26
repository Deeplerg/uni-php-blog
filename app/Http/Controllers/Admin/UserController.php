<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        // Пагинация по 10 пользователей для удобства
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        // Не даем админу менять роль самому себе (чтобы случайно не закрыть доступ)
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You can`t change the role for yourself.');
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in(['user', 'editor', 'admin'])],
        ]);

        $user->update($validated);

        return back()->with('status', "The role of the user {$user->name} has been updated to {$user->role}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your account through this panel.');
        }

        $user->delete();

        return back()->with('status', 'The user has been successfully deleted.');
    }
}
