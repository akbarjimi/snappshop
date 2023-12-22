<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferSuccessful extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'message' => trans('strings.transfer_succeed.message'),
            'transaction' => [
                'from' => $this->resource['from'],
                'to' => $this->resource['to'],
                'amount' => $this->resource['amount'],
                'timestamp' => $this->resource['timestamp'],
            ],
        ];
    }
}
