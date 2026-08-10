<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\Api\FileService;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    public function __construct(
        private FileService $fileService
    ) {}

    public function run(): void
    {
        $users = [
            [
                'name' => 'João Silva',
                'email' => 'joao.silva@codegram.test',
                'username' => 'joao.silva',
                'password' => 'password',
                'bio' => 'Desenvolvedor apaixonado por tecnologia.',
                'profile_photo' => null,
            ],
            [
                'name' => 'Maria Oliveira',
                'email' => 'maria.oliveira@codegram.test',
                'username' => 'maria.oliveira',
                'password' => 'password',
                'bio' => 'Frontend, café e código.',
                'profile_photo' => null,
            ],
            [
                'name' => 'Carlos Santos',
                'email' => 'carlos.santos@codegram.test',
                'username' => 'carlos.santos',
                'password' => 'password',
                'bio' => 'Backend developer e entusiasta de APIs.',
                'profile_photo' => null,
            ],
            [
                'name' => 'Ana Souza',
                'email' => 'ana.souza@codegram.test',
                'username' => 'ana.souza',
                'password' => 'password',
                'bio' => 'Aprendendo e compartilhando conhecimento.',
                'profile_photo' => null,
            ],
            [
                'name' => 'Pedro Costa',
                'email' => 'pedro.costa@codegram.test',
                'username' => 'pedro.costa',
                'password' => 'password',
                'bio' => 'PHP, Laravel e bancos de dados.',
                'profile_photo' => null,
            ],
            [
                'name' => 'Juliana Almeida',
                'email' => 'juliana.almeida@codegram.test',
                'username' => 'juliana.almeida',
                'password' => 'password',
                'bio' => 'Desenvolvedora full stack.',
                'profile_photo' => null,
            ],
            [
                'name' => 'Lucas Ferreira',
                'email' => 'lucas.ferreira@codegram.test',
                'username' => 'lucas.ferreira',
                'password' => 'password',
                'bio' => 'Sempre construindo alguma coisa.',
                'profile_photo' => null,
            ],
            [
                'name' => 'Beatriz Rodrigues',
                'email' => 'beatriz.rodrigues@codegram.test',
                'username' => 'beatriz.rodrigues',
                'password' => 'password',
                'bio' => 'Tecnologia, design e criatividade.',
                'profile_photo' => null,
            ],
            [
                'name' => 'Rafael Martins',
                'email' => 'rafael.martins@codegram.test',
                'username' => 'rafael.martins',
                'password' => 'password',
                'bio' => 'Estudante de programação.',
                'profile_photo' => null,
            ],
            [
                'name' => 'Camila Barbosa',
                'email' => 'camila.barbosa@codegram.test',
                'username' => 'camila.barbosa',
                'password' => 'password',
                'bio' => 'Compartilhando minha jornada na tecnologia.',
                'profile_photo' => null,
            ],
        ];

        foreach ($users as $index => $userData) {

            $userData['password'] = Hash::make($userData['password']);

            $user = User::create($userData);

            $avatar = "seed-images/avatars/" . ($index + 1) . ".jpg";
            $avatarAbsolutePath = Storage::disk('public')->path($avatar);

            $uploadedAvatar = new UploadedFile(
                $avatarAbsolutePath,
                basename($avatarAbsolutePath),
                Storage::disk('public')->mimeType($avatar),
                null,
                true
            );

            $storedPath = $this->fileService->storeProfilePhoto($uploadedAvatar);

            $user->update([
                'profile_photo' => $storedPath,
            ]);
        }
    }
}
