<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferFailed extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => false,
            'error' => [
                'message' => trans('strings.transfer_failed.insufficient_funds.message'),
                'code' => $this->resource['code'],
                'amount' => $this->resource['amount'],
                'details' => trans('strings.transfer_failed.insufficient_funds.details'),
            ],
        ];
    }
}
