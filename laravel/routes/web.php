<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return view('spa');
});

Route::get('api', function () {
    return response()->json([
        'message' => 'This is the root of the Chat App API.',
    ]);
});
