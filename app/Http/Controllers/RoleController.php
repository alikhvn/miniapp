<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function showRoleSelection()
    {
        return view('role-selection');
    }


    public function setRole(Request $request)
    {
        $request->validate([
            'role' => 'required|in:passenger,driver'
        ]);

        $user = Auth::user();
        $user->role = $request->role;

        if ($request->role === 'driver') {
            $request->validate([
                'car_info' => 'required|string|max:255',
                'license_plate' => 'required|string|max:20'
            ]);

            $user->car_info = $request->car_info;
            $user->license_plate = $request->license_plate;
        }

        $user->save();

        return redirect()
            ->route('home')
            ->with('notify', [
                'title' => 'Успешно!',
                'body'  => 'Роль успешно сохранена.',
                'status'=> 'success'
            ]);

    }

    public function switchRole()
    {
        $user = Auth::user();
        $user->role = $user->role === 'driver' ? 'passenger' : 'driver';
        $user->save();

        return redirect()->back()->with('success', 'Роль изменена!');
    }
}
