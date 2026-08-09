<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $comments = [
            // Post 1
            [2, 1, 'Ficou muito bom!'],
            [3, 1, 'Também estou trabalhando em algo parecido.'],
            [4, 1, 'Boa! Qual stack você está usando?'],

            // Post 2
            [3, 2, 'Esse projeto parece interessante.'],
            [6, 2, 'Curti bastante essa abordagem.'],

            // Post 3
            [1, 3, 'Essa interface ficou muito bonita!'],
            [4, 3, 'Gostei bastante desse resultado.'],
            [7, 3, 'Você pretende disponibilizar o projeto?'],

            // Post 4
            [1, 4, 'Esse detalhe ficou excelente.'],
            [5, 4, 'Também gosto bastante dessa abordagem.'],
            [9, 4, 'Muito bom!'],

            // Post 5
            [2, 5, 'Laravel facilita bastante esse tipo de coisa.'],
            [4, 5, 'Estou estudando Laravel também.'],
            [10, 5, 'Qual versão você está usando?'],

            // Post 6
            [1, 6, 'Depois de algumas horas sempre funciona 😂'],
            [7, 6, 'Conheço bem essa sensação.'],

            // Post 7
            [3, 7, 'Gostei das imagens!'],
            [8, 7, 'Esse setup parece muito confortável.'],

            // Post 8
            [2, 8, 'Banco de dados é uma parte que sempre dá trabalho.'],
            [6, 8, 'Boa explicação!'],
            [9, 8, 'Vou testar isso no meu projeto.'],

            // Post 9
            [1, 9, 'Essa tecnologia parece interessante.'],
            [5, 9, 'Também comecei a estudar isso recentemente.'],

            // Post 10
            [4, 10, 'Código limpo faz muita diferença.'],
            [7, 10, 'Excelente!'],

            // Post 11
            [2, 11, 'Estou passando exatamente por isso agora.'],
            [6, 11, 'Boa sorte no projeto!'],
            [10, 11, 'Depois mostra o resultado.'],

            // Post 12
            [1, 12, 'Gostei bastante dessa ideia.'],
            [3, 12, 'Muito interessante.'],

            // Post 13
            [2, 13, 'Pequenos projetos realmente ensinam muito.'],
            [8, 13, 'Concordo totalmente!'],
            [9, 13, 'Esse é o melhor jeito de aprender.'],
        ];

        foreach ($comments as [$userId, $postId, $comment]) {
            Comment::create([
                'user_id' => $userId,
                'post_id' => $postId,
                'comment' => $comment,
            ]);
        }
    }
}
