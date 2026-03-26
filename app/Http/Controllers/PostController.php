<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Post::query()
            ->with(['author', 'images'])
            ->when(
                ! $this->canModeratePosts($request->user()),
                function ($query) use ($request): void {
                    $query->where(function ($visiblePosts) use ($request): void {
                        $visiblePosts->where('status', 'published');

                        if ($request->user()) {
                            $visiblePosts->orWhere('user_id', $request->user()->id);
                        }
                    });
                }
            )
            ->latest()
            ->get();

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Post::class);

        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Post::class);

        $validated = $this->validatePost($request);

        $post = $request->user()->posts()->create([
            ...Arr::only($validated, ['title', 'body']),
            'status' => 'draft',
        ]);

        $this->storeImages($post, $request->file('images', []));

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        Gate::authorize('view', $post);

        $post->load(['author', 'images', 'comments.author']); // Эта штука подгружает связанного автора; без этого лаврушка будет выполнять отдельный запрос и спамить бд ненужным трафиком

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        Gate::authorize('update', $post);

        $post->load('images');

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

        $validated = $this->validatePost($request);

        $post->update(Arr::only($validated, ['title', 'body']));

        $this->deleteRemovedImages($post, $validated['removed_images'] ?? []);
        $this->storeImages($post, $request->file('images', []));

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index');
    }

    /**
     * Publish the specified post.
     */
    public function publish(Post $post, Request $request)
    {
        Gate::authorize('publish', $post);

        $post->publish($request->user());

        return redirect()->route('posts.show', $post);
    }

    /**
     * Return the specified post to drafts.
     */
    public function unpublish(Post $post)
    {
        Gate::authorize('unpublish', $post);

        $post->unpublish();

        return redirect()->route('posts.show', $post);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function validatePost(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:5120',
            'removed_images' => 'nullable|array',
            'removed_images.*' => 'integer',
        ]);
    }

    /**
     * @param  array<int, UploadedFile>|UploadedFile|null  $images
     */
    private function storeImages(Post $post, array|UploadedFile|null $images): void
    {
        foreach (Arr::wrap($images) as $image) {
            if (! $image instanceof UploadedFile) {
                continue;
            }

            $post->images()->create([
                'path' => $image->store('posts', 'local'),
            ]);
        }
    }

    /**
     * @param  array<int, int|string>  $imageIds
     */
    private function deleteRemovedImages(Post $post, array $imageIds): void
    {
        $post->images()
            ->whereKey($imageIds)
            ->get()
            ->each
            ->delete();
    }

    private function canModeratePosts(?User $user): bool
    {
        return $user !== null && in_array($user->role, ['editor', 'admin'], true);
    }
}
