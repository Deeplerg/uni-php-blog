<section class="space-y-6">
{{--    stolen from delete-user-form.blade.php--}}
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ $post->title }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Written by {{ $post->author->name }} on {{ $post->created_at->format('M d, Y') }}
        </p>
    </header>

    <div class="text-gray-900">
        {{ $post->body }}
    </div>

{{--    stolen from update-profile-information-form.blade.php--}}
    <div class="flex items-center gap-4">
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
