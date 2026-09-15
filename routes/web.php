<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/docs');
});

Route::get('/docs', function () {
    return response()->file(public_path('docs/index.html'));
});

Route::get('/api/documentation', function () {
    return redirect('/docs');
});

