<?php

namespace App\Notifications;

use App\Broadcasting\SMSChannel;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransactionTransferDebitNotification extends Notification
{
    use Queueable;

    private Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function via(object $notifiable): string
    {
        return SMSChannel::class;
    }

    public function toSMS(object $notifiable)
    {
        return [
            'receptor' => $notifiable->mobile,
            'message' => trans('strings.transactions.notifications.debit', [
                'name' => $notifiable->name,
                'amount' => $this->transaction->amount,
                'card' => $this->transaction->card->number,
            ]),
        ];
    }
}
