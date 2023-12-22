<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => null,
            'card_id' => null,
            'amount' => random_int(10000, 500000000),
            'type' => Arr::random([
                Transaction::DEPOSIT,
                Transaction::WITHDRAWAL,
                Transaction::TRANSFER_DEBIT,
                Transaction::TRANSFER_CREDIT,
                Transaction::FEE,
            ]),
            'refID' => Str::random(),
            'description' => $this->faker->text(),
        ];
    }

    public function fee()
    {
        return $this->state(function (array $attributes) {
            return [
                'account_id' => null,
                'card_id' => null,
                'amount' => Config::get("transactions.fee"),
                'type' => Transaction::FEE,
                'refID' => null,
                'description' => trans("strings.transactions.fee.message"),
            ];
        });
    }
}
