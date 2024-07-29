<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\CustomLoginController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Custom authentication routes
Route::prefix('auth')->group(function () {
    Route::post('login', [CustomLoginController::class, 'login'])->name('login');
    Route::post('logout', [CustomLoginController::class, 'logout'])->name('logout');
    Route::get('login', [CustomLoginController::class, 'showLoginForm'])->name('login');

    // Google authentication routes
    Route::prefix('google')->group(function () {
        Route::get('/', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
        Route::get('callback', [GoogleController::class, 'handleGoogleCallback']);
    });
});

// Routes for authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Super-admin routes
    Route::middleware(['role:admin|super-admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::middleware('role:admin|super-admin')->group(function () {
            Route::get('users', [UserController::class, 'index'])->name('users.index');
        });

        Route::middleware('role:super-admin')->prefix('users')->name('users.')->group(function () {
            Route::post('{user}/assign-roles', [UserController::class, 'assignRoles'])->name('assignRoles');
            Route::post('{user}/remove-roles', [UserController::class, 'removeRoles'])->name('removeRoles');
            Route::post('{user}/block', [UserController::class, 'blockUser'])->name('block');
            Route::post('{user}/unblock', [UserController::class, 'unblockUser'])->name('unblock');
        });
    });
});
