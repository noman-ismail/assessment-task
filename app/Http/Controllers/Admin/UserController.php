<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('admin.user.index', compact('users', 'roles'));
    }

    public function assignRoles(Request $request, User $user)
    {
        $user->syncRoles($request->roles);
        return response()->json(['success' => 'Roles assigned successfully.']);
    }

    public function removeRoles(Request $request, User $user)
    {
        $user->removeRole($request->role);
        return response()->json(['success' => 'Role removed successfully.']);
    }

    public function blockUser(User $user)
    {
        $user->is_blocked = true;
        $user->save();
        return response()->json(['success' => 'User blocked successfully.']);
    }

    public function unblockUser(User $user)
    {
        $user->is_blocked = false;
        $user->save();
        return response()->json(['success' => 'User unblocked successfully.']);
    }

}
