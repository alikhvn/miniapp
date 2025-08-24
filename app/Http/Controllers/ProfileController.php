<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only(['first_name', 'last_name', 'username', 'phone']));

        return redirect()->route('profile')->with('success', 'Профиль обновлен');
    }

    public function switchRole()
    {
        $user = Auth::user();
        $user->role = $user->role === 'driver' ? 'passenger' : 'driver';
        $user->save();

        return redirect()->back()->with('success', 'Роль изменена');
    }
}
