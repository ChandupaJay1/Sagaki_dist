<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('dashboard');

Route::get('/login', function () {
    return view('auth-signin');
})->name('login');

Route::get('/register', function () {
    return view('auth-signup');
})->name('register');

Route::get('/404', function () {
    return view('pages-404');
})->name('not-found');
