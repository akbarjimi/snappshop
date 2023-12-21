<?php

use App\Http\Middleware\EnsureAllDigisAreEnglish;
use App\Http\Requests\TransferFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware([EnsureAllDigisAreEnglish::class])->post('transfer', function (TransferFormRequest $request) {
    // check there is enough money
    // lock the money to not spend
    // transfer the money
    // unlock the money
    // send response
});

Route::get('top3', function (Request $request) {
    // report operation
});
