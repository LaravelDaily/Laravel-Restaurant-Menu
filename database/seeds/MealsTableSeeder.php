<?php

use App\Meal;
use Illuminate\Database\Seeder;

class MealsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            1 => [
                'Tasty pizza',
                'Tasty burger'
            ],
            2 => [
                'Juice',
                'Soda',
                'Milkshake',
                'Water'
            ],
            3 => [
                'Icecream',
                'Cake',
                'Cupcake'
            ]
        ];

        foreach ($categories as $id => $meals) {
            foreach($meals as $meal) {
                Meal::create([
                    'name' => $meal,
                    'category_id' => $id,
                    'price' => mt_rand(100,1000) / 100
                ]);
            }
        }
    }
}
