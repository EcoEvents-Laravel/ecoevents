<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 dummy users. We need users before we can create blogs.
        User::factory(10)->create();

        // Call our custom BlogSeeder
        $this->call([
            BlogSeeder::class,
        ]);
    }
}