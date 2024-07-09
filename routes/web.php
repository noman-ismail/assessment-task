<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\CustomLoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::post('login', [CustomLoginController::class, 'login'])->name('login');
Route::post('logout', [CustomLoginController::class, 'logout'])->name('logout');
Route::get('login', [CustomLoginController::class, 'showLoginForm'])->name('login');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


Route::middleware(['auth', 'role:super-admin'])->group(function () {
    Route::get('admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::post('admin/users/{user}/assign-roles', [App\Http\Controllers\Admin\UserController::class, 'assignRoles'])->name('admin.users.assignRoles');
    Route::post('admin/users/{user}/remove-roles', [App\Http\Controllers\Admin\UserController::class, 'removeRoles'])->name('admin.users.removeRoles');
    Route::post('admin/users/{user}/block', [App\Http\Controllers\Admin\UserController::class, 'blockUser'])->name('admin.users.block');
    Route::post('admin/users/{user}/unblock', [App\Http\Controllers\Admin\UserController::class, 'unblockUser'])->name('admin.users.unblock');
});
