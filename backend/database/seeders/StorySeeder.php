<?php

namespace Database\Seeders;

use App\Models\Story;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorySeeder extends Seeder
{
    public function run(): void
    {
        $stories = [
            
            ['user_id' => 1, 'image' => 'story-1.jpg', 'expires_at' => now()->addHours(20)],
            ['user_id' => 1, 'image' => 'story-2.jpg', 'expires_at' => now()->addHours(20)->addMinutes(5)],

            ['user_id' => 2, 'image' => 'story-3.jpg', 'expires_at' => now()->addHours(10)],

            ['user_id' => 3, 'image' => 'story-4.jpg', 'expires_at' => now()->addHours(23)],
            ['user_id' => 3, 'image' => 'story-5.jpg', 'expires_at' => now()->addHours(23)->addMinutes(5)],
            ['user_id' => 3, 'image' => 'story-6.jpg', 'expires_at' => now()->addHours(23)->addMinutes(10)],

            ['user_id' => 4, 'image' => 'story-7.jpg', 'expires_at' => now()->addMinutes(30)],


            ['user_id' => 5, 'image' => 'story-8.jpg', 'expires_at' => now()->subHours(6)],
            ['user_id' => 5, 'image' => 'story-9.jpg', 'expires_at' => now()->subHours(2)],

            ['user_id' => 6, 'image' => 'story-10.jpg', 'expires_at' => now()->subDay()],
        ];

        foreach ($stories as $storyData) {

            $source = "seed-images/stories/{$storyData['image']}";

            $extension = pathinfo($source, PATHINFO_EXTENSION);

            $filename = Str::uuid() . '.' . $extension;

            $destination = "stories/{$storyData['user_id']}/{$filename}";

            Storage::disk('public')->put(
                $destination,
                Storage::disk('public')->get($source)
            );

            Story::create([
                'user_id' => $storyData['user_id'],
                'path' => $destination,
                'expires_at' => $storyData['expires_at'],
            ]);
        }
    }
}
