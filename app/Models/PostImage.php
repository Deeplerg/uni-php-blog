<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    public const PRIVATE_DISK = 'local';

    protected $fillable = [
        'post_id',
        'path',
    ];

    protected static function booted(): void
    {
        static::deleting(function (PostImage $postImage): void {
            Storage::disk(self::PRIVATE_DISK)->delete($postImage->path);
        });
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
