<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Badge;
use App\Models\UserBadge;


class UserBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(5)->create();
        Badge::factory()->count(10)->create();
        UserBadge::factory()->count(20)->create()->each(function ($userBadge) {
            $userBadge->user_id = User::inRandomOrder()->first()->id;
            $userBadge->badge_id = Badge::inRandomOrder()->first()->id;
            $userBadge->save();
        });
    }
}
