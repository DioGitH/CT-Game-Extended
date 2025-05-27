@extends('lecturer.layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Manage Sections</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('lecturer.sections.create') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-500 transition duration-300 ease-in-out">
        + Add New Section
    </a>

    <div class="mt-6 overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200" id="sortable-sections">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($sections as $section)
                    <tr class="sortable-row hover:bg-gray-50 transition duration-150 ease-in-out"
                        data-id="{{ $section->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 cursor-move order-number">
                            ☰ {{ $section->order }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $section->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                            <a href="{{ route('lecturer.sections.edit', $section->id) }}"
                                class="bg-yellow-400 text-white px-3 py-1 rounded-md shadow hover:bg-yellow-300 transition duration-300">Edit</a>

                            <a href="{{ route('lecturer.challenges.index', ['section_id' => $section->id]) }}"
                                class="bg-indigo-500 text-white px-3 py-1 rounded-md shadow hover:bg-indigo-400 transition duration-300">Challenge</a>

                            <form action="{{ route('lecturer.sections.destroy', $section->id) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white px-3 py-1 rounded-md shadow hover:bg-red-400 transition duration-300">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $sections->links('pagination::tailwind') }}
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#sortable-sections tbody").sortable({
                update: function(event, ui) {
                    let orderedIds = $(".sortable-row").map(function() {
                        return $(this).data("id");
                    }).get();

                    $.ajax({
                        url: "{{ route('lecturer.sections.reorder') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            orderedIds: orderedIds
                        },
                        success: function(response) {
                            console.log(response.message);

                            $(".sortable-row").each(function(index) {
                                $(this).find(".order-number").text("☰ " + (index +
                                    1));
                            });
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            }).disableSelection();
        });
    </script>
@endsection
