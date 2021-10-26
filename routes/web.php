<?php

use Illuminate\Support\Facades\Route;

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

// Public Page
Route::group([
    'as' => 'public.'
], function(){
    Route::get('/', function () {
        return view('welcome');
    })->name('index');

    // Auth
    Auth::routes([
        'register' => false
    ]);
});

// System Page
Route::group([
    'prefix' => 's',
    'as' => 'system.',
    'middleware' => ['web', 'auth']
], function(){
    Route::any('/', function(){
        return redirect()->route('system.index');
    });
    // Dashboard
    Route::get('dashboard', \App\Http\Controllers\System\DashboardController::class)->name('index');
    // Attendance
    Route::resource('attendance', \App\Http\Controllers\System\AttendanceController::class);

    // Profile
    Route::resource('profile', \App\Http\Controllers\System\ProfileController::class);

    // Json
    Route::group([
        'prefix' => 'json',
        'as' => 'json.'
    ], function(){
        // Datatable
        Route::group([
            'prefix' => 'datatable',
            'as' => 'datatable.'
        ], function(){
            Route::get('attendance', [\App\Http\Controllers\System\AttendanceController::class, 'datatableAll'])->name('attendance.all');
            Route::get('task', [\App\Http\Controllers\System\TaskController::class, 'datatableAll'])->name('task.all');
        });
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
