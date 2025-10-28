
<?php

use Illuminate\Support\Facades\Route;

// In routes/web.php
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/', function () {

    return view('index');
});

