<?php

namespace App\Models;

use App\Events\TransactionSavedEvent;
use App\Exceptions\INSUFFICIENT_FUNDS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    const DEPOSIT = 10;
    const WITHDRAWAL = 20;
    const TRANSFER_DEBIT = 30;
    const TRANSFER_CREDIT = 40;
    const FEE = 50;

    protected $fillable = [
        'account_id',
        'card_id',
        'amount',
        'type',
        'balance',
        'refID',
        'description',
    ];


    protected $dispatchesEvents = [
        'created' => TransactionSavedEvent::class,
    ];

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

    public static function card2card(Card $origin, Card $destination, int $amount)
    {
        $fee = Config::get("transactions.fee");
        $available_balance = $origin->account->getBalance() - $fee;
        if ($available_balance < $amount) {
            throw new INSUFFICIENT_FUNDS($amount);
        }

        $refID = Str::random();
        try {
            DB::beginTransaction();

            $origin->account->transactions()->create([
                'account_id' => $origin->account_id,
                'card_id' => $origin->id,
                'amount' => $amount,
                'type' => Transaction::TRANSFER_DEBIT,
                'description' => "to card: " . $destination->number,
                'refID' => $refID,
            ])->updateBalance();

            $origin->account->transactions()->create([
                'card_id' => null,
                'amount' => Config::get("transactions.fee"),
                'type' => Transaction::FEE,
                'description' => trans("strings.transactions.fee.message"),
                'refID' => $refID,
            ])->updateBalance();

            $destination->account->transactions()->create([
                'card_id' => $destination->id,
                'amount' => $amount,
                'type' => Transaction::TRANSFER_CREDIT,
                'description' => "from card: " . $origin->number,
                'refID' => $refID,
            ])->updateBalance();
            DB::commit();
            return true;
        } catch (Throwable $throwable) {
            DB::rollBack();
            report($throwable);
            return false;
        }
    }


    public function updateBalance(): void
    {
        $this->update([
            'balance' => $this->account->transactions()
                    ->whereKeyNot($this->id)->latest($this->getKeyName())
                    ->first()?->balance + $this->getSignedAmount(),
        ]);
    }
}
