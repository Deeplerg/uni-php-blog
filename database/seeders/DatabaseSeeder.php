<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => 'admin123'
        ]);

        $post1 = Post::create([
            'title' => 'Test 1',
            'body' => 'text',
            'user_id' => $admin->id,
        ]);

        $post2 = Post::create([
            'title' => 'Test 2',
            'body' => 'bruh',
            'user_id' => $admin->id,
        ]);

        Comment::create([
            'post_id' => $post1->id,
            'user_id' => $admin->id,
            'body' => 'Comment 1',
        ]);

        Comment::create([
            'post_id' => $post1->id,
            'user_id' => $admin->id,
            'body' => 'com2',
        ]);

        Comment::create([
            'post_id' => $post2->id,
            'user_id' => $admin->id,
            'body' => '3',
        ]);
    }
}
