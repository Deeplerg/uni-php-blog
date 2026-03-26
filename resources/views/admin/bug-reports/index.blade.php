<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bug Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('status') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold">Date</th>
                        <th class="px-6 py-3 text-sm font-semibold">Code</th>
                        <th class="px-6 py-3 text-sm font-semibold">User</th>
                        <th class="px-6 py-3 text-sm font-semibold">URL / Message</th>
                        <th class="px-6 py-3 text-sm font-semibold">Action</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y">
                    @foreach ($reports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm whitespace-nowrap">{{ $report->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-red-600">{{ $report->error_code }}</td>
                            <td class="px-6 py-4 text-sm">
                                {{ $report->user ? $report->user->name : 'Guest' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="text-gray-500 italic mb-1">{{ $report->url }}</div>
                                <div class="text-gray-900">{{ $report->message }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.bug-reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Delete this report?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="p-4">{{ $reports->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
