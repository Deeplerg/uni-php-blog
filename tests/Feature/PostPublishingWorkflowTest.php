<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostPublishingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_created_post_starts_as_draft(): void
    {
        $author = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($author)->post(route('posts.store'), [
            'title' => 'Draft post',
            'body' => 'Draft body',
        ]);

        $response->assertRedirect(route('posts.index'));

        $post = Post::where('title', 'Draft post')->firstOrFail();

        $this->assertSame(Post::STATUS_DRAFT, $post->status);
        $this->assertNull($post->published_at);
        $this->assertNull($post->published_by);
    }

    public function test_guest_can_only_see_published_posts(): void
    {
        $author = User::factory()->create([
            'role' => 'user',
        ]);

        Post::create([
            'title' => 'Published post',
            'body' => 'Published body',
            'user_id' => $author->id,
            'status' => Post::STATUS_PUBLISHED,
            'published_at' => now(),
            'published_by' => $author->id,
        ]);

        Post::create([
            'title' => 'Hidden draft',
            'body' => 'Draft body',
            'user_id' => $author->id,
            'status' => Post::STATUS_DRAFT,
        ]);

        $response = $this->get(route('posts.index'));

        $response->assertOk();
        $response->assertSee('Published post');
        $response->assertDontSee('Hidden draft');
    }

    public function test_editor_can_publish_author_draft(): void
    {
        $author = User::factory()->create([
            'role' => 'user',
        ]);

        $editor = User::factory()->create([
            'role' => 'editor',
        ]);

        $post = Post::create([
            'title' => 'Needs moderation',
            'body' => 'Body',
            'user_id' => $author->id,
            'status' => Post::STATUS_DRAFT,
        ]);

        $response = $this->actingAs($editor)->patch(route('posts.publish', $post));

        $response->assertRedirect(route('posts.show', $post));

        $post->refresh();

        $this->assertSame(Post::STATUS_PUBLISHED, $post->status);
        $this->assertNotNull($post->published_at);
        $this->assertSame($editor->id, $post->published_by);
    }

    public function test_author_cannot_edit_own_published_post(): void
    {
        $author = User::factory()->create([
            'role' => 'user',
        ]);

        $post = Post::create([
            'title' => 'Locked post',
            'body' => 'Body',
            'user_id' => $author->id,
            'status' => Post::STATUS_PUBLISHED,
            'published_at' => now(),
            'published_by' => $author->id,
        ]);

        $response = $this->actingAs($author)->patch(route('posts.update', $post), [
            'title' => 'Updated title',
            'body' => 'Updated body',
        ]);

        $response->assertForbidden();
    }
}