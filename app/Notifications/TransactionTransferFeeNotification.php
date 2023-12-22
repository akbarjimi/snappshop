<?php

namespace App\Notifications;

use App\Broadcasting\SMSChannel;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransactionTransferFeeNotification extends Notification
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
            'message' => trans('strings.transactions.notifications.fee', [
                'name' => $notifiable->name,
                'amount' => $this->transaction->amount,
                'account' => $this->transaction->account_id,
            ]),
        ];
    }
}
