<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class TicketsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Simulated asset filenames (these should exist in your storage or public folder)
          $assets = [
            
            'images/test4.jpeg',
            'images/test5.jpg',
            'images/test6.jpg',
            'images/test7.jpg',
            'images/test8.jpg',
        ];


        // Optional: predefined statuses, priorities, sizes
        $statuses = ['O', 'C', 'B', 'P'];
        $priorities = ['L', 'M', 'H', 'C'];
        $sizes = ['XS', 'S', 'M', 'L', 'XL'];

        for ($i = 0; $i < 100; $i++) {
            $created = $faker->dateTimeBetween('-60 days', 'now');
            $updated = $faker->dateTimeBetween($created, 'now');
            Ticket::create([
                'title' => $faker->sentence(3),
                'description' => $faker->paragraph,
                'status' => $faker->randomElement($statuses),
                'priority' => $faker->randomElement($priorities),
                'assignee' => $faker->numberBetween(1, 80),
                'stakeholders' => json_encode($faker->randomElements(range(1, 80), rand(1, 3))),
                'tshirt_size' => $faker->randomElement($sizes),
                'assets' => json_encode($faker->randomElements($assets, 3)),
                'created_at' => $created,
                'updated_at' => $updated,
            ]);
        }
    }
}