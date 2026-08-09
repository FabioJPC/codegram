<?php

namespace Database\Seeders;

use App\Models\Follow;
use Illuminate\Database\Seeder;

class FollowSeeder extends Seeder
{
    public function run(): void
    {
        $follows = [
            // João
            [1, 2],
            [1, 3],
            [1, 4],

            // Maria
            [2, 1],
            [2, 3],
            [2, 5],

            // Carlos
            [3, 1],
            [3, 2],
            [3, 6],

            // Ana
            [4, 2],
            [4, 5],
            [4, 7],

            // Pedro
            [5, 1],
            [5, 4],
            [5, 8],

            // Juliana
            [6, 1],
            [6, 3],
            [6, 9],

            // Lucas
            [7, 2],
            [7, 6],
            [7, 10],

            // Beatriz
            [8, 3],
            [8, 5],
            [8, 9],

            // Rafael
            [9, 1],
            [9, 6],
            [9, 10],

            // Camila
            [10, 2],
            [10, 7],
            [10, 8],
        ];

        foreach ($follows as [$followerId, $followingId]) {
            Follow::create([
                'follower_id' => $followerId,
                'following_id' => $followingId,
            ]);
        }
    }
}
