<?php

use Illuminate\Support\Facades\Route;

//route resource
Route::resource('/', \App\Http\Controllers\CrudController::class);
Route::resource('/crud', \App\Http\Controllers\CrudController::class);