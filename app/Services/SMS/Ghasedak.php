<?php

namespace App\Services\SMS;

use Ghasedak\GhasedakApi;
use Throwable;

class Ghasedak implements SmsServiceInterface
{
    public $receptor = null;

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
            $api = new GhasedakApi(env('GHASEDAKAPI_KEY'));
            $api->SendSimple(
                $this->receptor,  // receptor
                $this->message, // message
                env('GHASEDAKAPI_SENDER')
            );
        } catch (Throwable $e) {
            report($e);
        }
    }
}
