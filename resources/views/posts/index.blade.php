<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Posts') }}
            </h2>

            <a href="{{ route('posts.create') }}">
                <x-primary-button type="button">
                    {{ __('New post') }}
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    {{-- stolen from resources/views/posts/show.blade.php --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @foreach ($posts as $post)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg
                            flex justify-between items-start">

                    <div class="max-w-2xl">
                        <a href="{{ route('posts.show', $post) }}" class="text-xl font-bold text-gray-900 hover:underline">
                            {{ $post->title }}
                        </a>
                        <p class="mt-1 text-sm text-gray-500">
                            Written by {{ $post->author->name }} on {{ $post->created_at->format('M d, Y') }}
                        </p>
                        <div class="mt-4 text-gray-800">
{{--                            cut it a bit just in case it's a big post--}}
                            {{ \Illuminate\Support\Str::limit($post->body, 150) }}
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('posts.edit', $post) }}" class="text-gray-500 hover:text-gray-900" title="Edit">
                            <x-heroicon-o-pencil class="w-5 h-5" />
                        </a>

                        <form method="POST" action="{{ route('posts.destroy', $post) }}"
                              onsubmit="return confirm('Are you sure? Deleting post: {{ $post->title }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-900" title="Delete">
                                <x-heroicon-o-trash class="w-5 h-5" />
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
