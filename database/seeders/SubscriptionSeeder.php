<?php

namespace Database\Seeders;

use App\Models\SubscriptionOption;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubscriptionOption::insert([
            [
                'period' => 1,
                'price' => 10000,
            ],
            [
                'period' => 2,
                'price' => 15000,
            ],
            [
                'period' => 3,
                'price' => 20000,
            ],
        ]);
    }
}
