<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function assignRole()
    {
        // Find the user with ID 1, or create a new user if it doesn't exist
        $user = User::find(1);

        if (!$user) {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => bcrypt('password'), // Ensure you hash the password
            ]);
        }

        // Assign the 'super-admin' role to the user
        $user->assignRole('super-admin');

        return response()->json(['message' => 'Role assigned successfully'], 200);
    }
}
