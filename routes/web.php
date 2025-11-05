
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

