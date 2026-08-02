<?php

namespace App\Services\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait AuthorizeOwnership
{
    protected function authorizeOwnership(User $user, Model $model)
    {
        if ($user->id !== $model->user_id) {
            throw new HttpException(403, 'Você não tem permissão para realizar esta ação');
        }
    }
}
