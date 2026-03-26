<section class="space-y-6">
{{--    stolen from delete-user-form.blade.php--}}
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ $post->title }}
        </h2>

        <div class="mt-3 flex flex-wrap items-center gap-2">
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $post->isPublished() ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                {{ ucfirst($post->status) }}
            </span>

            @if ($post->isPublished() && $post->published_at)
                <span class="text-xs text-gray-500">
                    Published {{ $post->published_at->format('M d, Y H:i') }}
                </span>
            @endif
        </div>

        <p class="mt-1 text-sm text-gray-600">
            Written by {{ $post->author->name }} on {{ $post->created_at->format('M d, Y') }}
        </p>
    </header>

    <div class="text-gray-900">
        {{ $post->body }}
    </div>

    @if ($post->images->isNotEmpty())
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($post->images as $image)
                <a href="{{ route('posts.images.show', [$post, $image]) }}" target="_blank" rel="noreferrer">
                    <img src="{{ route('posts.images.show', [$post, $image]) }}"
                         alt="Image for {{ $post->title }}"
                         class="h-64 w-full rounded-lg object-cover shadow-sm transition hover:opacity-90" />
                </a>
            @endforeach
        </div>
    @endif

{{--    stolen from update-profile-information-form.blade.php--}}
    <div class="flex items-center gap-4">
        @can('publish', $post)
            <form method="POST" action="{{ route('posts.publish', $post) }}">
                @csrf
                @method('PATCH')
                <x-primary-button>
                    {{ __('Publish') }}
                </x-primary-button>
            </form>
        @endcan

        @can('unpublish', $post)
            <form method="POST" action="{{ route('posts.unpublish', $post) }}">
                @csrf
                @method('PATCH')
                <x-secondary-button type="submit">
                    {{ __('Move to Drafts') }}
                </x-secondary-button>
            </form>
        @endcan

        @can('update', $post)

            <a href="{{ route('posts.edit', $post) }}">
                <x-secondary-button>
                    {{ __('Edit') }}
                </x-secondary-button>
            </a>
        @endcan

        @can('delete', $post)
            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                  onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <x-danger-button>
                    {{ __('Delete') }}
                </x-danger-button>
            </form>
        @endcan
    </div>
</section>
