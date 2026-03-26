<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Posts') }}
            </h2>

            @auth
                @can('create', App\Models\Post::class)
                    <a href="{{ route('posts.create') }}">
                        <x-primary-button type="button">
                            {{ __('New post') }}
                        </x-primary-button>
                    </a>
                @endcan
            @endauth
        </div>
    </x-slot>

    {{-- stolen from resources/views/posts/show.blade.php --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @forelse ($posts as $post)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg
                            flex justify-between items-start">

                    <div class="flex max-w-2xl gap-4">
                        @if ($post->images->isNotEmpty())
                            <a href="{{ route('posts.show', $post) }}" class="shrink-0">
                                <img src="{{ route('posts.images.show', [$post, $post->images->first()]) }}"
                                     alt="Preview for {{ $post->title }}"
                                     class="h-24 w-24 rounded-lg object-cover" />
                            </a>
                        @endif

                        <div>
                        <a href="{{ route('posts.show', $post) }}" class="text-xl font-bold text-gray-900 hover:underline">
                            {{ $post->title }}
                        </a>
                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $post->isPublished() ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($post->status) }}
                            </span>

                            @if ($post->isPublished() && $post->published_at)
                                <span class="text-xs text-gray-500">
                                    Published {{ $post->published_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            Written by {{ $post->author->name }} on {{ $post->created_at->format('M d, Y') }}
                        </p>
                        <div class="mt-4 text-gray-800">
{{--                            cut it a bit just in case it's a big post--}}
                            {{ \Illuminate\Support\Str::limit($post->body, 150) }}
                        </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        @can('update', $post)
                            <a href="{{ route('posts.edit', $post) }}" class="text-gray-500 hover:text-gray-900" title="Edit">
                                <x-heroicon-o-pencil class="w-5 h-5" />
                            </a>
                        @endcan

                        @can('delete', $post)
                            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                                  onsubmit="return confirm('Are you sure? Deleting post: {{ $post->title }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-900" title="Delete">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                            </form>
                        @endcan
                    </div>

                </div>
            @empty
                <div class="p-6 bg-white shadow sm:rounded-lg text-gray-600">
                    No posts available yet.
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
