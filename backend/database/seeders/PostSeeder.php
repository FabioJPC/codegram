<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'user_id' => 1,
                'caption' => 'Começando mais um projeto. Bora codar! 🚀',
                'images' => [
                    'post-1.jpg',
                ],
            ],

            [
                'user_id' => 1,
                'caption' => 'Um pouco do que estou trabalhando hoje.',
                'images' => [
                    'post-2.jpg',
                    'post-3.jpg',
                ],
            ],

            [
                'user_id' => 2,
                'caption' => 'Finalmente terminei essa interface!',
                'images' => [
                    'post-4.jpg',
                ],
            ],

            [
                'user_id' => 2,
                'caption' => 'Alguns detalhes do projeto.',
                'images' => [
                    'post-5.jpg',
                    'post-6.jpg',
                    'post-7.jpg',
                ],
            ],

            [
                'user_id' => 3,
                'caption' => 'Hoje foi dia de estudar APIs e Laravel.',
                'images' => [
                    'post-8.jpg',
                ],
            ],

            [
                'user_id' => 3,
                'caption' => 'Testando uma nova arquitetura para o projeto.',
                'images' => [
                    'post-9.jpg',
                    'post-10.jpg',
                ],
            ],

            [
                'user_id' => 4,
                'caption' => 'Mais um dia aprendendo programação.',
                'images' => [
                    'post-11.jpg',
                    'post-14.jpg',
                ],
            ],

            [
                'user_id' => 5,
                'caption' => 'Banco de dados também faz parte da brincadeira.',
                'images' => [
                    'post-13.jpg',
                ],
            ],

            [
                'user_id' => 6,
                'caption' => 'Depois de algumas horas, finalmente funcionou.',
                'images' => [
                    'post-15.jpg',
                    'post-16.jpg',
                    'post-10.jpg',
                ],
            ],

            [
                'user_id' => 7,
                'caption' => 'Um pouco de código para começar o dia.',
                'images' => [
                    'post-12.jpg',
                ],
            ],

            [
                'user_id' => 8,
                'caption' => 'Tentando deixar o código cada vez mais limpo.',
                'images' => [
                    'post-16.jpg',
                    'post-15.jpg',
                ],
            ],

            [
                'user_id' => 9,
                'caption' => 'Estudando uma tecnologia nova hoje.',
                'images' => [
                    'post-16.jpg',
                ],
            ],

            [
                'user_id' => 10,
                'caption' => 'Pequenos projetos também ensinam muito.',
                'images' => [
                    'post-14.jpg',
                    'post-15.jpg',
                ],
            ],
        ];

        foreach ($posts as $postData) {

            $images = $postData['images'];

            unset($postData['images']);

            $post = Post::create($postData);

            foreach ($images as $position => $image) {

                $source = "seed-images/posts/{$image}";

                $extension = pathinfo($source, PATHINFO_EXTENSION);

                $filename = Str::uuid() . '.' . $extension;

                $destination = "posts/{$post->user_id}/{$post->id}/{$filename}";

                Storage::disk('public')->put(
                    $destination,
                    Storage::disk('public')->get($source)
                );

                PostImage::create([
                    'post_id' => $post->id,
                    'path' => $destination,
                    'position' => $position,
                ]);
            }
        }
    }
}
