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
            'message' => 'Money transferred successfully.',
            'transaction' => [
                'from' => $request->post('origin'),
                'to' => $request->post('destination'),
                'amount' => $request->post('amount'),
                'timestamp' => now(),
            ],
        ];
    }
}
