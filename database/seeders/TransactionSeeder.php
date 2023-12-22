<?php

namespace Database\Seeders;

use App\Exceptions\INSUFFICIENT_FUNDS;
use App\Models\Account;
use App\Models\Card;
use App\Models\Transaction;
use Exception;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws Exception
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

        $cards = Card::query()->inRandomOrder()->get();

        /** @var Card $origin */
        foreach ($cards as $origin) {
            $amount = random_int(10000, 500000000);
            $destination = $cards->except($origin->getKey())
                ->where("account_id", "!=", $origin->account_id)
                ->random();
            try {
                Transaction::card2card($origin, $destination, $amount);
            } catch (INSUFFICIENT_FUNDS $exception) {
                continue;
            }
        }
    }
}
