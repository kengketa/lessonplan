<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSetupController;
use Inertia\Inertia;
use App\Models\Role;
use App\Http\Controllers\Dashboard\SchoolController;
use App\Http\Controllers\Dashboard\GradeController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\PageController;

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
    return redirect()->route('login');
});
Route::get('/test', function () {
    return Inertia::render('Dashboard/Reports/Print');
});

Route::get("posts", [PostController::class, "index"])->name("posts.index");

//Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->name('dashboard');

// reset, setup password, pin for user
Route::get("setup-password/{token}/{key}", [UserSetupController::class, "setup"])->name("user-setup.show");
Route::post("do-setup", [UserSetupController::class, "doSetup"])->name("user-setup.save");

Route::prefix('dashboard')->middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::middleware(["role:".Role::ROLE_ADMIN.'|'.Role::ROLE_SUPER_ADMIN])->group(function () {

        //users
        Route::get("users", [UserController::class, "index"])->name("dashboard.users.index");
        Route::get("users/create", [UserController::class, "create"])->name("dashboard.users.create");
        Route::get("users/{user}", [UserController::class, "show"])->name("dashboard.users.show");
        Route::post("users", [UserController::class, "store"])->name("dashboard.users.store");
        Route::get("users/{user}/edit", [UserController::class, "edit"])->name("dashboard.users.edit");
        Route::put("users/{user}", [UserController::class, "update"])->name("dashboard.users.update");
        Route::delete("users/{user}", [UserController::class, "destroy"])->name("dashboard.users.destroy");
        Route::put("users/{user}/restore", [UserController::class, "restore"])->name("dashboard.users.restore");

        //schools
        Route::get("schools/create", [SchoolController::class, "create"])->name("dashboard.schools.create");
        Route::post("schools/{school}/add-subject",
            [SchoolController::class, "addSubject"])->name("dashboard.schools.add_subject");
        Route::post("schools/{school}/delete-subject",
            [SchoolController::class, "deleteSubject"])->name("dashboard.schools.delete_subject");
        Route::post("schools/{school}/add-teacher",
            [SchoolController::class, "addTeacher"])->name("dashboard.schools.add_teacher");
        Route::post("schools/{school}/remove-teacher",
            [SchoolController::class, "removeTeacher"])->name("dashboard.schools.remove_teacher");
        Route::post("schools", [SchoolController::class, "store"])->name("dashboard.schools.store");
        Route::get("schools/{school}/edit", [SchoolController::class, "edit"])->name("dashboard.schools.edit");
        Route::put("schools/{school}", [SchoolController::class, "update"])->name("dashboard.schools.update");
        Route::delete("schools/{school}", [SchoolController::class, "destroy"])->name("dashboard.schools.destroy");

        //grades
        Route::post("grades", [GradeController::class, "store"])->name("dashboard.grades.store");
        Route::get("grades/{grade}", [GradeController::class, "show"])->name("dashboard.grades.show");
        Route::delete("grades/{grade}", [GradeController::class, "destroy"])->name("dashboard.grades.destroy");

        //reports
        Route::post("reports/{report}/approve",
            [ReportController::class, "approve"])->name("dashboard.reports.approve");

    });

    Route::middleware(["role:".Role::ROLE_ADMIN.'|'.Role::ROLE_SUPER_ADMIN.'|'.Role::ROLE_TEACHER])->group(function () {
        Route::get("/", [PageController::class, "dashboard"])->name("dashboard");
        Route::get("schools", [SchoolController::class, "index"])->name("dashboard.schools.index");
        Route::get("schools/{school}", [SchoolController::class, "show"])->name("dashboard.schools.show");

        //reports
        Route::get("schools/{school}/reports/create",
            [ReportController::class, "create"])->name("dashboard.reports.create");
        Route::post("reports", [ReportController::class, "store"])->name("dashboard.reports.store");
        Route::get("reports/{report}/edit", [ReportController::class, "edit"])->name("dashboard.reports.edit");
        Route::put("reports/{report}", [ReportController::class, "update"])->name("dashboard.reports.update");
        Route::post("reports/{report}/next",
            [ReportController::class, "next"])->name("dashboard.reports.next");

        Route::post("print-reports", [ReportController::class, "print"])->name("dashboard.reports.print");

    });

});
Route::middleware(["auth"])->group(function () {
    /* auto_generate_route  (DO NOT REMOVE THIS LINE)*/


});
