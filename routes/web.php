<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSetupController;
use Inertia\Inertia;
use App\Models\Role;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get("posts", [PostController::class, "index"])->name("posts.index");

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

// reset, setup password, pin for user
Route::get("setup-password/{token}/{key}", [UserSetupController::class, "setup"])->name("user-setup.show");
Route::post("do-setup", [UserSetupController::class, "doSetup"])->name("user-setup.save");

Route::prefix('dashboard')->middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::middleware(["role:" . Role::ROLE_ADMIN.'|'.Role::ROLE_SUPER_ADMIN])->group(function () {
        //users
        Route::get("users", [UserController::class, "index"])->name("dashboard.users.index");
        Route::get("users/create", [UserController::class, "create"])->name("dashboard.users.create");
        Route::get("users/{user}", [UserController::class, "show"])->name("dashboard.users.show");
        Route::post("users", [UserController::class, "store"])->name("dashboard.users.store");
        Route::get("users/{user}/edit", [UserController::class, "edit"])->name("dashboard.users.edit");
        Route::put("users/{user}", [UserController::class, "update"])->name("dashboard.users.update");
        Route::delete("users/{user}", [UserController::class, "destroy"])->name("dashboard.users.destroy");
        Route::put("users/{user}/restore", [UserController::class, "restore"])->name("dashboard.users.restore");
    });
});
Route::middleware(["auth"])->group(function () {
    /* auto_generate_route  (DO NOT REMOVE THIS LINE)*/

});
