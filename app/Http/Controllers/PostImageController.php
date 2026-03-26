<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PostImageController extends Controller
{
    /**
     * Display the specified image.
     */
    public function show(Post $post, PostImage $image): BinaryFileResponse
    {
        abort_unless($image->post_id === $post->id, 404);

        Gate::authorize('view', $post);

        $disk = Storage::disk(PostImage::PRIVATE_DISK);

        abort_unless($disk->exists($image->path), 404);

        return response()->file(
            $disk->path($image->path),
            [
                'Cache-Control' => $post->isPublished() ? 'public, max-age=3600' : 'private, no-store',
            ]
        );
    }
}