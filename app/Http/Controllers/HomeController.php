<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $auth = Auth::user();
        $user = User::with('roles')->where('id' , $auth->id)->first();
        if ($user->hasRole('super-admin')) {
            return view('admin.dashboard' ,  compact('user')); // Super Admin Dashboard
        }

        if ($user->hasRole('admin')) {
            return view('admin.dashboard' ,  compact('user')); // Admin Dashboard
        }

        if ($user->hasRole('user')) {
            return view('user.dashboard' ,  compact('user')); // User Dashboard
        }

        return view('home' ,  compact('user')); // Default Dashboard
    }
}
