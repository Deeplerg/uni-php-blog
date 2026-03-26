<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Post $post): bool
    {
        if ($post->isPublished()) {
            return true;
        }

        if (! $user) {
            return false;
        }

        if (in_array($user->role, ['editor', 'admin'], true)) {
            return true;
        }

        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['user', 'editor', 'admin'], true);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $post->user_id && $post->isDraft();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $post->user_id && $post->isDraft();
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Post $post): bool
    {
        return in_array($user->role, ['editor', 'admin'], true) && $post->isDraft();
    }

    /**
     * Determine whether the user can unpublish the model.
     */
    public function unpublish(User $user, Post $post): bool
    {
        return in_array($user->role, ['editor', 'admin'], true) && $post->isPublished();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
