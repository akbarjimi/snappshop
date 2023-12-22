<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    const DEPOSIT = 10;
    const WITHDRAWAL = 20;
    // to customer
    const TRANSFER_DEBIT = 30;

    // from customer
    const TRANSFER_CREDIT = 40;
    const FEE = 50;

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Account::class);
    }

    public function getSignedAmount()
    {
        if (in_array($this->type, [static::WITHDRAWAL, static::TRANSFER_DEBIT, static::FEE,])) {
            return $this->amount * -1;
        }

        return $this->amount;
    }
}
