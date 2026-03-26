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

        @if(session('status'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        @if($code >= 400)
            <div class="mt-8 max-w-md mx-auto p-6 bg-white shadow-md rounded-lg text-left">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">Report this issue</h3>
                <form action="{{ route('bug-reports.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="error_code" value="{{ $code }}">
                    <input type="hidden" name="url" value="{{ request()->fullUrl() }}">

                    <div>
                        <x-input-label for="message" :value="__('What happened?')" />
                        <textarea id="message" name="message" rows="3"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                  placeholder="Describe what you were doing..." required></textarea>
                    </div>

                    <div class="mt-4">
                        <x-primary-button>Send Report</x-primary-button>
                    </div>
                </form>
            </div>
        @endif

        <div class="mt-6">
            <a href="/" class="text-indigo-600 hover:underline">Return to home</a>
        </div>
    </div>
</x-guest-layout>
