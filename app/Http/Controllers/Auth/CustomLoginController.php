<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CustomLoginController extends Controller
{
    use \Illuminate\Foundation\Auth\AuthenticatesUsers;

    /**
     * Override the authenticated method to include is_blocked check.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->is_blocked) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => [trans('User is Blocked by Super Admin')],
            ]);
        }

        return redirect()->intended($this->redirectPath());
    }
}
