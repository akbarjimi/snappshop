<?php

namespace App\Listeners;

use App\Events\TransactionSavedEvent;
use App\Models\Transaction;
use App\Notifications\TransactionTransferCreditNotification;
use App\Notifications\TransactionTransferDebitNotification;
use App\Notifications\TransactionTransferFeeNotification;

class TransactionSavedListener
{
    public function handle(TransactionSavedEvent $event): void
    {
        /** @var Transaction $transaction */
        $transaction = $event->transaction;
        switch ($transaction->type) {
            case Transaction::WITHDRAWAL:
            case Transaction::DEPOSIT:
                return;
            case Transaction::TRANSFER_DEBIT:
                $transaction->account->user->notify(new TransactionTransferDebitNotification($transaction));
                break;
            case Transaction::FEE:
                $transaction->account->user->notify(new TransactionTransferFeeNotification($transaction));
                break;
            case Transaction::TRANSFER_CREDIT:
                $transaction->account->user->notify(new TransactionTransferCreditNotification($transaction));
                break;
        }
    }
}
