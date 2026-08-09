<?php

namespace Database\Seeders;

use App\Models\Like;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    public function run(): void
    {
        $likes = [
            // Post 1
            [2, 1],
            [3, 1],
            [4, 1],
            [5, 1],

            // Post 2
            [2, 2],
            [3, 2],
            [6, 2],

            // Post 3
            [1, 3],
            [4, 3],
            [7, 3],
            [8, 3],

            // Post 4
            [1, 4],
            [3, 4],
            [5, 4],
            [9, 4],

            // Post 5
            [2, 5],
            [4, 5],
            [6, 5],
            [10, 5],

            // Post 6
            [1, 6],
            [5, 6],
            [7, 6],

            // Post 7
            [3, 7],
            [4, 7],
            [8, 7],
            [9, 7],

            // Post 8
            [1, 8],
            [2, 8],
            [6, 8],

            // Post 9
            [2, 9],
            [5, 9],
            [10, 9],

            // Post 10
            [1, 10],
            [4, 10],
            [7, 10],
            [8, 10],

            // Post 11
            [3, 11],
            [6, 11],
            [9, 11],

            // Post 12
            [1, 12],
            [2, 12],
            [5, 12],
            [10, 12],

            // Post 13
            [3, 13],
            [4, 13],
            [7, 13],
            [9, 13],
        ];

        foreach ($likes as [$userId, $postId]) {
            Like::create([
                'user_id' => $userId,
                'post_id' => $postId,
            ]);
        }
    }
}
