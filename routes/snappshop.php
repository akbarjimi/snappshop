<?php

use App\Http\Controllers\Top3UsersController;
use App\Http\Controllers\TransferController;
use App\Http\Middleware\EnsureAllDigisAreEnglish;
use Illuminate\Support\Facades\Route;


Route::middleware([EnsureAllDigisAreEnglish::class])->post('transfer', TransferController::class);
Route::get('top3', Top3UsersController::class);
