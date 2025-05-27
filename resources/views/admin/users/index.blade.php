@extends('admin.layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-700">👥 User Management</h2>
            <a href="{{ route('admin.users.create') }}"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-200">
                + Add User
            </a>
        </div>

        <!-- Search & Pagination -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
            <!-- Search -->
            <div class="relative w-full md:w-1/2 mb-4 md:mb-0">
                <input type="text" id="search" placeholder="🔍 Search by name, email, or role..."
                    class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" onkeyup="filterUsers()">
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mb-4">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center space-x-2">
                    <label for="perPage" class="text-gray-700">Show</label>
                    <select name="perPage" id="perPage" onchange="this.form.submit()" class="border-gray-300 rounded-md">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        <option value="all" {{ $perPage == 'all' ? 'selected' : '' }}>Show All</option>
                    </select>
                    <span class="text-gray-700">entries</span>
                </form>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- User Table -->
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 rounded-lg overflow-hidden shadow-lg">
                <thead class="bg-blue-600 text-white text-left">
                    <tr>
                        <th class="px-4 py-3 text-center">Profile</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="user-table" class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-100 transition duration-200">
                            <!-- Profile Photo -->
                            <td class="px-4 py-3 text-center">
                                <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('images/default-avatar.png') }}"
                                    alt="{{ $user->name }}"
                                    class="w-10 h-10 object-cover rounded-full border-2 border-gray-300 shadow-sm">
                            </td>

                            <!-- Name -->
                            <td class="px-4 py-3 font-medium text-gray-800 whitespace-nowrap">
                                {{ $user->name }}
                            </td>

                            <!-- Email -->
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $user->email }}</td>

                            <!-- Role -->
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                <span class="bg-gray-200 text-gray-700 text-sm px-3 py-1 rounded-full shadow-sm">
                                    {{ $user->roles->pluck('name')->join(', ') }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 flex justify-center space-x-3">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                    class="bg-blue-500 text-white px-3 py-2 rounded-lg text-sm shadow hover:bg-blue-600 transition">
                                    ℹ️ Info
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="bg-yellow-500 text-white px-3 py-2 rounded-lg text-sm shadow hover:bg-yellow-600 transition">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white px-3 py-2 rounded-lg text-sm shadow hover:bg-red-700 transition">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links-->
        @if ($users->hasPages())
            <div class="mt-6 flex justify-center">
                <nav class="flex space-x-1">
                    {{-- Previous Page --}}
                    @if ($users->onFirstPage())
                        <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-l-lg cursor-not-allowed">← Prev</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}"
                            class="px-4 py-2 bg-blue-500 text-white rounded-l-lg hover:bg-blue-600 transition">← Prev</a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <span class="px-4 py-2 bg-blue-600 text-white font-bold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 transition">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page --}}
                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}"
                            class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600 transition">Next →</a>
                    @else
                        <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-r-lg cursor-not-allowed">Next →</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>

    <script>
        function filterUsers() {
            let input = document.getElementById('search').value.toLowerCase();
            let rows = document.querySelectorAll('#user-table tr');

            rows.forEach(row => {
                let name = row.cells[1].textContent.toLowerCase();
                let email = row.cells[2].textContent.toLowerCase();
                let role = row.cells[3].textContent.toLowerCase();

                if (name.includes(input) || email.includes(input) || role.includes(input)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
@endsection
