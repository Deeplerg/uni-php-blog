<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-sm font-semibold text-gray-600">ID</th>
                            <th class="px-6 py-3 text-sm font-semibold text-gray-600">Name</th>
                            <th class="px-6 py-3 text-sm font-semibold text-gray-600">Email</th>
                            <th class="px-6 py-3 text-sm font-semibold text-gray-600">Role</th>
                            <th class="px-6 py-3 text-sm font-semibold text-gray-600">Deletion</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $user->id }}</td>
                                <td class="px-6 py-4 text-sm">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" 
                                            class="text-sm rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 {{ $user->id === auth()->id() ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                            <option value="editor" {{ $user->role === 'editor' ? 'selected' : '' }}>Editor</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete a user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold">
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-xs italic">It's you</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
