<x-app-layout>
    {{-- stolen from resources/views/posts/show.blade.php --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Edit post') }}
                        </h2>
                    </header>

                    <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $post->title)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="body" :value="__('Body')" />
                            {{--stolen from text-input.blade.php--}}
                            <textarea id="body" name="body" rows="6"
                                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                      required>{{ old('body', $post->body) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('body')" />
                        </div>

                        @if ($post->images->isNotEmpty())
                            <div class="space-y-3">
                                <x-input-label :value="__('Current images')" />

                                <div class="grid gap-4 sm:grid-cols-2">
                                    @foreach ($post->images as $image)
                                        <label class="block overflow-hidden rounded-lg border border-gray-200 bg-gray-50 p-3">
                                            <img src="{{ route('posts.images.show', [$post, $image]) }}" alt="Post image" class="h-40 w-full rounded-md object-cover" />

                                            <span class="mt-3 flex items-center gap-2 text-sm text-gray-700">
                                                <input type="checkbox" name="removed_images[]" value="{{ $image->id }}"
                                                       @checked(in_array($image->id, old('removed_images', [])))>
                                                Remove this image
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('removed_images')" />
                                <x-input-error class="mt-2" :messages="$errors->get('removed_images.*')" />
                            </div>
                        @endif

                        <div>
                            <x-input-label for="images" :value="__('Add more images')" />
                            <input id="images" name="images[]" type="file" accept="image/*" multiple
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <p class="mt-2 text-sm text-gray-500">
                                You can upload up to 5 images, 5 MB each.
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('images')" />
                            <x-input-error class="mt-2" :messages="$errors->get('images.*')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                            <a href="{{ route('posts.index') }}"
                               class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>
                </section>
            </div>

        </div>
    </div>
</x-app-layout>
