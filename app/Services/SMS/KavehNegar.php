<?php

namespace App\Services\SMS;

use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Kavenegar\KavenegarApi;

class KavehNegar implements SmsServiceInterface
{
    public $receptor = [];
    public $message = null;

    public function setReceptor(string $receptor)
    {
        $this->receptor = $receptor;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function send()
    {
        try {
            $api = new KavenegarApi(env('KAVEHNEGAR_API_KEY'));
            $sender = env('KAVEHNEGAR_SENDER');
            $message = $this->message;
            $receptor = $this->receptor;
            $api->Send($sender, $receptor, $message);
            // Skipped this part, there was no time.
        } catch (ApiException $e) {
            // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
            report($e);
        } catch (HttpException $e) {
            // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
            report($e);
        }
    }
}
