@extends('admin.layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-700">🎓 Student Management</h2>
            <a href="{{ route('admin.students.create') }}"
                class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-blue-700 transition duration-200">
                + Add Student
            </a>
        </div>

        <!-- Search & Pagination -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
            <!-- Search -->
            <div class="relative w-full md:w-1/2 mb-4 md:mb-0">
                <input type="text" id="search" name="search" value="{{ request('search') }}"
                    placeholder="🔍 Search by name, email, program..."
                    class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" onkeyup="liveSearch()">
            </div>

            <!-- Sorting Reset Button -->
            <a href="{{ route('admin.students.index', ['perPage' => request('perPage')]) }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600 transition duration-200">
                🔄 Reset Sorting
            </a>

            <!-- Pagination Dropdown -->
            <form method="GET" action="{{ route('admin.students.index') }}" class="flex items-center space-x-2">
                <label for="perPage" class="text-gray-700">Show</label>
                <select name="perPage" id="perPage" onchange="this.form.submit()" class="border-gray-300 rounded-md">
                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('perPage') == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                    <option value="all" {{ request('perPage') == 'all' ? 'selected' : '' }}>Show All</option>
                </select>
                <span class="text-gray-700">entries</span>
            </form>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- Student Table -->
        <div class="overflow-x-auto bg-gray-50 rounded-lg shadow-md">
            <table class="w-full border border-gray-300 rounded-lg overflow-hidden shadow-lg">
                <!-- Table Header with Sorting -->
                <thead class="bg-blue-600 text-white text-left">
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap">Photo</th>
                        <th class="px-4 py-3">
                            <a
                                href="{{ route('admin.students.index', ['sort' => 'user_name', 'order' => $sortField == 'user_name' && $sortOrder == 'asc' ? 'desc' : 'asc', 'perPage' => request('perPage')]) }}">
                                Name
                                @if ($sortField == 'user_name')
                                    {!! $sortOrder == 'asc' ? '⬆️' : '⬇️' !!}
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3">
                            <a
                                href="{{ route('admin.students.index', ['sort' => 'prodi', 'order' => $sortField == 'prodi' && $sortOrder == 'asc' ? 'desc' : 'asc', 'perPage' => request('perPage')]) }}">
                                Program
                                @if ($sortField == 'prodi')
                                    {!! $sortOrder == 'asc' ? '⬆️' : '⬇️' !!}
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3">
                            <a
                                href="{{ route('admin.students.index', ['sort' => 'class', 'order' => $sortField == 'class' && $sortOrder == 'asc' ? 'desc' : 'asc', 'perPage' => request('perPage')]) }}">
                                Class
                                @if ($sortField == 'class')
                                    {!! $sortOrder == 'asc' ? '⬆️' : '⬇️' !!}
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3">
                            <a
                                href="{{ route('admin.students.index', ['sort' => 'semester', 'order' => $sortField == 'semester' && $sortOrder == 'asc' ? 'desc' : 'asc', 'perPage' => request('perPage')]) }}">
                                Semester
                                @if ($sortField == 'semester')
                                    {!! $sortOrder == 'asc' ? '⬆️' : '⬇️' !!}
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody id="students-table-body" class="bg-white divide-y divide-gray-200">
                    @forelse ($students as $student)
                        <tr class="hover:bg-gray-100 transition duration-200">
                            <td class="px-4 py-3 flex justify-center">
                                <img src="{{ $student->user->profile_photo ? asset('storage/' . $student->user->profile_photo) : asset('images/default-avatar.png') }}"
                                    alt="{{ $student->user->name }}"
                                    class="w-12 h-12 object-cover rounded-full border-2 border-gray-300 shadow-sm">
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800 truncate max-w-[150px]">
                                {{ $student->user->name }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $student->prodi ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700 text-center">{{ $student->class ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700 text-center">{{ $student->semester ?? '-' }}</td>
                            <td class="px-4 py-3 flex justify-center space-x-3">
                                <a href="{{ route('admin.students.show', $student->id) }}"
                                    class="bg-blue-500 text-white px-2 py-2 rounded-lg text-sm shadow hover:bg-blue-600 transition">
                                    ℹ️ Info
                                </a>
                                <a href="{{ route('admin.students.edit', $student->id) }}"
                                    class="bg-yellow-500 text-white px-2 py-2 rounded-lg text-sm shadow hover:bg-yellow-600 transition">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this student?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 text-white px-2 py-2 rounded-lg text-sm shadow hover:bg-red-700 transition">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links-->
        @if ($pagination)
            <!-- Hanya tampilkan pagination jika ada paginasi -->
            <div class="mt-6 flex justify-center">
                <nav class="flex space-x-1">
                    {{-- Previous Page --}}
                    @if ($students->onFirstPage())
                        <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-l-lg cursor-not-allowed">← Prev</span>
                    @else
                        <a href="{{ $students->previousPageUrl() }}"
                            class="px-4 py-2 bg-blue-500 text-white rounded-l-lg hover:bg-blue-600 transition">← Prev</a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                        @if ($page == $students->currentPage())
                            <span class="px-4 py-2 bg-blue-600 text-white font-bold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 transition">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page --}}
                    @if ($students->hasMorePages())
                        <a href="{{ $students->nextPageUrl() }}"
                            class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600 transition">Next →</a>
                    @else
                        <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-r-lg cursor-not-allowed">Next →</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>

    <script>
        function liveSearch() {
            let query = document.getElementById('search').value;
            let perPage = document.getElementById('perPage') ? document.getElementById('perPage').value : 10;

            fetch(`{{ route('admin.students.index') }}?search=${query}&perPage=${perPage}`)
                .then(response => response.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    let newTableBody = doc.querySelector('#students-table-body');
                    document.getElementById('students-table-body').innerHTML = newTableBody.innerHTML;
                });
        }
    </script>
@endsection
