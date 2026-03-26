<x-guest-layout>
    <div class="text-center">
        <h1 class="text-9xl font-bold text-gray-200">{{ $code }}</h1>

        <p class="text-2xl font-medium mt-4 mb-6">
            @if($code == 404) Page not found
            @elseif($code == 403) Access denied
            @elseif($code == 500) Server exception
            @else Whoops! Something went wrong
            @endif
        </p>

        <p class="text-gray-500 mt-2 mb-6">
            🐎🌌
        </p>

        <a href="/" class="text-indigo-600 hover:underline">Return to home</a>
    </div>
</x-guest-layout>
