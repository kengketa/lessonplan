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
use App\Http\Controllers\ClockInController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\Dashboard\MeetingController;
use App\Http\Controllers\Dashboard\AgendaController;
use App\Http\Controllers\VocabController;
use App\Http\Controllers\MisbehaviorController;

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
})->name('baseurl');

Route::prefix('tessaban5')->group(function () {
    Route::get("/",
        [VocabController::class, "index"])->name("vocabs.index");
    Route::get("vocabs/{grade}",
        [VocabController::class, "fetchVocabs"])->name("vocabs.fetch");
    Route::put("vocabs/{vocab}",
        [VocabController::class, "update"])->name("vocabs.update");
    Route::post("accept-cookie",
        [VocabController::class, "acceptCookie"])->name("vocabs.accept_cookie");

});

// reset, setup password, pin for user
Route::get("setup-password/{token}/{key}", [UserSetupController::class, "setup"])->name("user-setup.show");
Route::post("do-setup", [UserSetupController::class, "doSetup"])->name("user-setup.save");
Route::post('send-reset-password-request',
    [UserSetupController::class, 'sentResetPassword'])->name('reset-password.sent');

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
        Route::post("schools/{school}/bulk-lesson-plan-approve",
            [SchoolController::class, "bulkApprove"])->name("dashboard.schools.bulk_approve");
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

        //clockins
        Route::get("clock-ins",
            [ClockInController::class, "index"])->name("dashboard.clock_ins.index");
        Route::post("clock-ins/generate-report",
            [ClockInController::class, "generateReport"])->name("dashboard.clock_ins.generate_report");

        //meetings
        Route::get("meetings/create", [MeetingController::class, "create"])->name("dashboard.meetings.create");
        Route::post("meetings", [MeetingController::class, "store"])->name("dashboard.meetings.store");
        Route::get("meetings/{meeting}/edit", [MeetingController::class, "edit"])->name("dashboard.meetings.edit");
        Route::put("meetings/{meeting}", [MeetingController::class, "update"])->name("dashboard.meetings.update");
        Route::delete("meetings/{meeting}", [MeetingController::class, "destroy"])->name("dashboard.meetings.destroy");
        Route::post("meetings/{meeting}/achieve",
            [MeetingController::class, "achieve"])->name("dashboard.meetings.achieve");
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
        Route::post("reports/{report}/previous",
            [ReportController::class, "previous"])->name("dashboard.reports.previous");
        Route::delete("reports/{report}",
            [ReportController::class, "destroy"])->name("dashboard.reports.destroy");

        Route::post("print-reports", [ReportController::class, "print"])->name("dashboard.reports.print");
        Route::get("print-reports-preview",
            [ReportController::class, "printPreview"])->name("dashboard.reports.print_preview");
        Route::post("print-reports-generate-link",
            [ReportController::class, "generateLink"])->name("dashboard.reports.generate_link");

        //clock in
        Route::post("clock-in",
            [ClockInController::class, "in"])->name("dashboard.clock_ins.in");
        Route::post("clock-out",
            [ClockInController::class, "out"])->name("dashboard.clock_ins.out");

        //my time sheet
        Route::get("my-time-sheets", [TeacherController::class, "myTimeSheet"])->name("dashboard.my_time_sheet");

        //meetings
        Route::get("meetings", [MeetingController::class, "index"])->name("dashboard.meetings.index");
        Route::get("meetings/{meeting}", [MeetingController::class, "show"])->name("dashboard.meetings.show");

        //misbehavior student report
        Route::get("schools/{school}/misbehavior-report",
            [MisbehaviorController::class, "index"])->name('dashboard.misbehaviors.index');

    });

});

Route::get("global-reports", [ReportController::class, "globalReports"])->name("reports.global"); // to be removed later
Route::get("global-reports/{globalReport}",
    [ReportController::class, "showGlobalReports"])->name("reports.global.show");
Route::get("clock-ins/report-preview",
    [ClockInController::class, "clockInPreview"])->name("dashboard.clock_ins.preview");


Route::middleware(["auth"])->group(function () {
    /* auto_generate_route  (DO NOT REMOVE THIS LINE)*/

    //agendas
    Route::get("agendas", [AgendaController::class, "index"])->name("dashboard.agendas.index");
    Route::get("agendas/create", [AgendaController::class, "create"])->name("dashboard.agendas.create");
    Route::get("agendas/{agenda}", [AgendaController::class, "show"])->name("dashboard.agendas.show");
    Route::post("agendas", [AgendaController::class, "store"])->name("dashboard.agendas.store");
    Route::get("agendas/{agenda}/edit", [AgendaController::class, "edit"])->name("dashboard.agendas.edit");
    Route::put("agendas/{agenda}", [AgendaController::class, "update"])->name("dashboard.agendas.update");
    Route::delete("agendas/{agenda}", [AgendaController::class, "destroy"])->name("dashboard.agendas.destroy");


});
