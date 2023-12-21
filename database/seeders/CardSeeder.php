<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Card;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Account::all() as $account) {
            Card::factory()
                ->count(random_int(1, 3))
                ->for($account)
                ->create();
        }
    }
}
