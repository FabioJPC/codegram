<?php

namespace App\Models;

use App\Services\Api\FileService;
use Dom\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'caption'
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    public function images(): HasMany
    {
        return $this->hasMany(
            PostImage::class,
            'post_id',
            'id'
        )->orderBy('position');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(
            Comment::class,
            'post_id',
            'id'
        )->latest();
    }

    public function likes(): HasMany
    {
        return $this->hasMany(
            Like::class,
            'post_id',
            'id'
        );
    }

    protected static function booted(): void
    {
        static::deleting(function (Post $post) {
            $fileService = app(FileService::class);
            $fileService->deleteDirectory("posts/{$post->user_id}/{$post->id}");
        });
    }
}
