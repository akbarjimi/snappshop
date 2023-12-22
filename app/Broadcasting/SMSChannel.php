<?php

namespace App\Broadcasting;

use App\Services\SMS\SmsServiceInterface;
use Illuminate\Notifications\Notification;

class SMSChannel
{
    private SmsServiceInterface $smsService;

    public function __construct(SmsServiceInterface $smsService)
    {
        $this->smsService = $smsService;
    }

    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toSMS($notifiable);
        $this->smsService->setReceptor($message['receptor']);
        $this->smsService->setMessage($message['message']);
        $this->smsService->send();
    }
}
