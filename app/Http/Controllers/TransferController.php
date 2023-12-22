<?php

namespace App\Http\Controllers;

use App\Exceptions\INSUFFICIENT_FUNDS;
use App\Http\Requests\TransferFormRequest;
use App\Http\Resources\TransferSuccessful;
use App\Models\Card;
use App\Models\Transaction;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class TransferController extends Controller
{
    public function __invoke(TransferFormRequest $request)
    {
        try {
            /** @var Card $originCard */
            $originCard = Card::whereNumber($request->post('origin'))->first();

            /** @var Card $destinationCard */
            $destinationCard = Card::where('number', $request->post('destination'))->first();

            Transaction::card2card($originCard, $destinationCard, $request->post('amount'));

            return new TransferSuccessful([
                'from' => $originCard->number,
                'to' => $destinationCard->number,
                'amount' => $request->post('amount'),
                'timestamp' => now()->toString(),
            ]);
        } catch (INSUFFICIENT_FUNDS $exception) {
            DB::rollBack();
            throw $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            report($throwable);
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
