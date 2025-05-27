@extends('lecturer.layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Student Progress</h1>
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <input type="text" id="searchInput" placeholder="Search name or level..."
            class="w-full sm:w-1/3 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-300 focus:outline-none"
            onkeyup="filterTable()">
    </div>
    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-600">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Rank</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">EXP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Streak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Weekly Score
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total Score</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($students as $student)
                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 student-name">
                            {{ $student->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 student-level">
                            {{ $student->ranks->last()?->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student->exp }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student->streak }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student->weekly_score }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $student->total_score }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <a href="{{ route('lecturer.students.show', $student->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded-md shadow hover:bg-yellow-400 transition duration-300">
                                Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4 flex justify-center">
        {{ $students->links('pagination::tailwind') }}
    </div>

    <script>
        function filterTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const name = row.querySelector(".student-name")?.textContent.toLowerCase() || '';
                const level = row.querySelector(".student-level")?.textContent.toLowerCase() || '';

                if (name.includes(filter) || level.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
@endsection
