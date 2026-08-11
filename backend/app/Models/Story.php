<?php

namespace App\Models;

use App\Services\Api\FileService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = [
        'user_id',
        'path',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
    }

    protected static function booted(): void
    {
        static::deleting(function (Story $story) {
            $fileService = app(FileService::class);
            $fileService->deleteImage($story->path);
        });
    }
}
