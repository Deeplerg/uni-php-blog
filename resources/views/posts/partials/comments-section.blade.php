<section>
{{--    stolen from update-profile-information-form.blade.php--}}
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Comments') }} ({{ $post->comments->count() }})
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Make comments") }}
        </p>
    </header>

    @auth
        <form method="post" action="{{ route('comments.store', $post) }}" class="mt-6 space-y-6">
            @csrf

            <div>
                <x-input-label for="body" :value="__('Write a comment')" />

{{--                stolen from text-input.blade.php--}}
                <textarea id="body" name="body" rows="3"
                          class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                          required></textarea>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Post Comment') }}</x-primary-button>
                </div>
            </div>
        </form>
    @else
{{--                stolen from update-profile-information-form.blade.php--}}
        <p class="mt-6 text-sm text-gray-800">
            <a href="{{ route('login') }}"
               class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log in') }}
            </a>
            to leave a comment
        </p>
    @endauth

    <div class="mt-6 space-y-6 border-t border-gray-200 pt-6">
        @forelse($post->comments as $comment)
            <div>
                <h2 class="text-sm font-medium text-gray-900">
                    {{ $comment->author->name }}
                </h2>
                <p class="text-sm text-gray-600">
                    {{ $comment->created_at->diffForHumans() }}
                </p>

                <p class="mt-2 text-sm text-gray-900">
                    {{ $comment->body }}
                </p>

                @can('delete', $comment)
                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="mt-2">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="text-sm text-red-600 hover:text-red-900">
                            {{ __('Delete') }}
                        </button>
                    </form>
                @endcan
            </div>
        @empty
            <p class="mt-1 text-sm text-gray-600">{{ __('No comments :( ') }}</p>
        @endforelse
    </div>
</section>
