<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Home');
});

Route::get('/{any?}', function () {
    return inertia('App');
})->where('any', '.*');
