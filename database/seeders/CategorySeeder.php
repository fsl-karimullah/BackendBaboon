<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::insert([
            [
                'name' => 'Petualangan',
            ],
            [
                'name' => 'Fantasi',
            ],
            [
                'name' => 'Sejarah',
            ],
            [
                'name' => 'Sastra',
            ],
            [
                'name' => 'Humor',
            ],
            [
                'name' => 'Horor',
            ],
            [
                'name' => 'Romansa',
            ],
            [
                'name' => 'Misteri',
            ],
            [
                'name' => 'Fiksi Ilmiah',
            ],
        ]);
    }
}
