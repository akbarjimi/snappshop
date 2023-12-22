<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Card;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seed money
        /** @var Account $account */
        foreach (Account::all() as $account) {
            $transaction = Transaction::factory()->create([
                'account_id' => $account->id,
                'type' => Transaction::DEPOSIT,
                'description' => "seed money",
                'refID' => Str::random(),
            ]);
            $this->updateBalance($transaction);
        }

        $cards = Card::all();
        $fee = Config::get("transactions.fee", 5000);

        /** @var Card $origin */
        foreach ($cards as $origin) {
            $max = $origin->account->getBalance() - $fee;
            if ($max < 10000) {
                continue;
            }

            $refID = Str::random();
            $amount = random_int(10000, $max);
            $destination = $cards->except($origin->getKey())->where("account_id", "!=", $origin->account_id)->random();

            $transaction = Transaction::factory()->create([
                'account_id' => $origin->account_id,
                'card_id' => $origin->id,
                'amount' => $amount,
                'type' => Transaction::TRANSFER_DEBIT,
                'description' => "to card: " . $destination->number,
                'refID' => $refID,
            ]);
            $this->updateBalance($transaction);


            $transaction = Transaction::factory()->fee()->create([
                'account_id' => $origin->account_id,
                'refID' => $refID,
            ]);
            $this->updateBalance($transaction);

            $transaction = Transaction::factory()->create([
                'account_id' => $destination->account_id,
                'card_id' => $destination->id,
                'amount' => $amount,
                'type' => Transaction::TRANSFER_CREDIT,
                'description' => "from card: " . $origin->number,
                'refID' => $refID,
            ]);
            $this->updateBalance($transaction);
        }
    }

    public function updateBalance(Model $transaction): void
    {
        $account = $transaction->account;
        $latest = $account->transactions()->whereKeyNot($transaction->id)->latest($transaction->getKeyName())->first();
        $balance = $latest?->balance + $transaction->getSignedAmount();
        $transaction->update([
            'balance' => $balance,
        ]);
    }
}
