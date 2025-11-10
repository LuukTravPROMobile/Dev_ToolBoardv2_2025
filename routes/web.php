
<?php

use Illuminate\Support\Facades\Route;

// In routes/web.php
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);

use App\Http\Controllers\Auth\LoginController;

Route::post('/login', [LoginController::class, 'login'])->name('login');


Route::get('/', function () {

    return view('index');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware('role:admin'); // Toegang alleen voor admin gebruikers

Route::get('/user', function () {
    return view('user.dashboard');
})->middleware('role:user'); // Toegang alleen voor gewone gebruikers

Route::get('/guest', function () {
    return view('guest.dashboard');
})->middleware('role:guest'); // Toegang alleen voor gasten

Route::get('/shared', function () {
    return view('shared.dashboard');
})->middleware('role:admin,user'); // Toegang voor admin en gewone gebruikers

Route::get('/profile', function () {
    return view('profile.dashboard');
})->middleware('auth'); // Toegang voor alle geauthenticeerde gebruikers

Route::get('/public', function () {
    return view('public.dashboard');
}); // Toegang voor iedereen, geen middleware

Route::get('/settings', function () {
    return view('settings.dashboard');
})->middleware('role:admin')->middleware('auth'); // Toegang alleen voor geauthenticeerde admin gebruikers

Route::get('/reports', function () {
    return view('reports.dashboard');
})->middleware('role:admin,user')->middleware('auth'); // Toegang voor geauthenticeerde admin en gewone gebruikers

Route::get('/help', function () {
    return view('help.dashboard');
})->middleware('role:guest,user,admin'); // Toegang voor alle rollen

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware('auth'); // Toegang voor alle geauthenticeerde gebruikers

Route::get('/analytics', function () {
    return view('analytics.index');
})->middleware('role:admin'); // Toegang alleen voor admin gebruikers

Route::get('/notifications', function () {
    return view('notifications.index');
})->middleware('role:user,admin'); // Toegang voor gewone gebruikers en admin gebruikers

Route::get('/messages', function () {
    return view('messages.index');
})->middleware('auth'); // Toegang voor alle geauthenticeerde gebruikers




