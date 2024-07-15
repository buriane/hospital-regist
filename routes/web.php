<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('regis/index');
});

Route::get('/jadwal', function () {
    return view('jadwal/index');
});

Route::get('/info', function () {
    return view('info/index');
});

