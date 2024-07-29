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
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();
        return view('admin.user.index', compact('users', 'roles'));
    }

    public function assignRoles(Request $request, User $user)
    {
    try{
        $user->syncRoles($request->roles);
        return response()->json(['success' => 'Roles assigned successfully.']);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 403);
    }
    }

    public function removeRoles(Request $request, User $user)
    {
        $user->removeRole($request->role);
        return response()->json(['success' => 'Role removed successfully.']);
    }

    public function blockUser(User $user)
    {
        try {
            $user->is_blocked = true;
            $user->save();
            return response()->json(['success' => 'User blocked successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }


    public function unblockUser(User $user)
    {
        try {
            $user->is_blocked = false;
            $user->save();
            return response()->json(['success' => 'User unblocked successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

}
