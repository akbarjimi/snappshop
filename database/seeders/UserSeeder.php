<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dispatcher = User::getEventDispatcher();
        User::unsetEventDispatcher();
        User::factory()
            ->count(5)
            ->create();
        User::setEventDispatcher($dispatcher);
    }
}
