<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_editor_can_create_post_with_images(): void
    {
        Storage::fake('local');

        $user = User::factory()->create([
            'role' => 'editor',
        ]);

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'Post with gallery',
            'body' => 'Body for post with gallery.',
            'images' => [
                UploadedFile::fake()->image('first.jpg'),
                UploadedFile::fake()->image('second.png'),
            ],
        ]);

        $response->assertRedirect(route('posts.index'));

        $post = Post::where('title', 'Post with gallery')->firstOrFail();

        $this->assertCount(2, $post->images);

        foreach ($post->images as $image) {
            Storage::disk('local')->assertExists($image->path);
        }
    }

    public function test_author_can_remove_existing_images_and_add_new_ones_when_updating_post(): void
    {
        Storage::fake('local');

        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $post = Post::create([
            'title' => 'Editable post',
            'body' => 'Post body',
            'user_id' => $user->id,
        ]);

        Storage::disk('local')->put('posts/old-image.jpg', 'old-image-content');

        $imageToDelete = $post->images()->create([
            'path' => 'posts/old-image.jpg',
        ]);

        $response = $this->actingAs($user)->patch(route('posts.update', $post), [
            'title' => 'Editable post',
            'body' => 'Updated body',
            'removed_images' => [$imageToDelete->id],
            'images' => [
                UploadedFile::fake()->image('fresh-image.jpg'),
            ],
        ]);

        $response->assertRedirect(route('posts.index'));

        $post->refresh();

        $this->assertSame('Updated body', $post->body);
        $this->assertCount(1, $post->images);
        Storage::disk('local')->assertMissing('posts/old-image.jpg');
        Storage::disk('local')->assertExists($post->images->first()->path);
    }

    public function test_guest_cannot_open_image_from_draft_post(): void
    {
        Storage::fake('local');

        $author = User::factory()->create([
            'role' => 'user',
        ]);

        $post = Post::create([
            'title' => 'Draft with image',
            'body' => 'Hidden body',
            'user_id' => $author->id,
            'status' => Post::STATUS_DRAFT,
        ]);

        Storage::disk('local')->put('posts/draft-image.jpg', 'secret-image');

        $image = $post->images()->create([
            'path' => 'posts/draft-image.jpg',
        ]);

        $response = $this->get(route('posts.images.show', [$post, $image]));

        $response->assertForbidden();
    }

    public function test_guest_can_open_image_from_published_post(): void
    {
        Storage::fake('local');

        $author = User::factory()->create([
            'role' => 'user',
        ]);

        $post = Post::create([
            'title' => 'Published with image',
            'body' => 'Visible body',
            'user_id' => $author->id,
            'status' => Post::STATUS_PUBLISHED,
            'published_at' => now(),
            'published_by' => $author->id,
        ]);

        Storage::disk('local')->put('posts/published-image.jpg', 'visible-image');

        $image = $post->images()->create([
            'path' => 'posts/published-image.jpg',
        ]);

        $response = $this->get(route('posts.images.show', [$post, $image]));

        $response->assertOk();
    }
}

