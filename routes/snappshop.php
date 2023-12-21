<?php

use App\Exceptions\INSUFFICIENT_FUNDS;
use App\Http\Middleware\EnsureAllDigisAreEnglish;
use App\Http\Requests\TransferFormRequest;
use App\Http\Resources\TransferSuccessful;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
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
    try {
        DB::beginTransaction();
        $originCardNumber = $request->post('origin');
        /** @var Card $originCard */
        $originCard = Card::where('number', $originCardNumber)->first();
        // lock the money to not spend
        $originAccount = $originCard->account()->sharedLock()->first();
        // check there is enough money
        if ($originAccount->balance < $request->post('amount')) {
            throw new INSUFFICIENT_FUNDS($request->post('amount'));
        }

        $destinationCardNumber = $request->post('destination');
        /** @var Card $destinationCard */
        $destinationCard = Card::where('number', $destinationCardNumber)->first();
        $destinationAccount = $destinationCard->account()->lockForUpdate()->first();

        // transfer the money
        $originAccount->decrement('balance', $request->amount);
        $destinationAccount->increment('balance', $request->amount);

        // unlock the money
        DB::commit();
        // send response
        return new TransferSuccessful($destinationAccount);
    } catch (INSUFFICIENT_FUNDS $exception) {
        DB::rollBack();
        throw $exception;
    } catch (Throwable $throwable) {
        DB::rollBack();
        report($throwable);
        abort(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
});

Route::get('top3', function (Request $request) {
    // report operation
});
