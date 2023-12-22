<?php

use App\Http\Controllers\TransferController;
use App\Http\Middleware\EnsureAllDigisAreEnglish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware([EnsureAllDigisAreEnglish::class])->post('transfer', TransferController::class);

Route::get('top3', function (Request $request) {
    // report operation
});
