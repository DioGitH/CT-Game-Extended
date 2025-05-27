@extends('lecturer.layouts.app')

@section('content')
    <div class="relative">
        <!-- Notifikasi -->
        @if (session('status') === 'success')
            <div id="notification"
                class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-4 rounded-md shadow-lg transition-opacity duration-500 z-50">
                🎉 Challenge berhasil dibuat! Ayo cek tantangan barumu di daftar!
            </div>
        @elseif (session('status') === 'error')
            <div id="notification"
                class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-4 rounded-md shadow-lg transition-opacity duration-500 z-50">
                😢 Oops! Gagal membuat challenge. Coba cek lagi data yang kamu masukkan.
            </div>
        @elseif (session('status') === 'delete-success')
            <div id="notification"
                class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-4 rounded-md shadow-lg transition-opacity duration-500 z-50">
                🗑️ Challenge {{ session('deleted_title') }} berhasil dihapus!
            </div>
        @elseif (session('status') === 'delete-error')
            <div id="notification"
                class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-red-600 text-white px-6 py-4 rounded-md shadow-lg transition-opacity duration-500 z-50">
                ⚠️ Oops! Gagal menghapus challenge. Coba ulangi lagi nanti.
            </div>
        @endif
    </div>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manage Challenges</h1>

        <div class="flex items-center space-x-4">
            <form method="GET" action="{{ route('lecturer.challenges.index') }}">
                <select name="section_id" onchange="this.form.submit()" class="border border-gray-300 rounded-md p-2">
                    <option value="">-- All Sections --</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}" {{ $sectionSearch == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('lecturer.challenges.create', ['section_id' => request('section_id')]) }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-500 transition duration-300 ease-in-out">
                Add New Challenge
            </a>
        </div>
    </div>
    <div class="mt-6 overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Section</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Total EXP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Total Score</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($challenges as $challenge)
                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-2.5 py-1 rounded">
                                {{ $challenge->section->name ?? 'No Section' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $challenge->title }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $challenge->total_exp }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $challenge->total_score }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                            <a href="{{ route('lecturer.challenges.edit', $challenge->id) }}"
                                class="bg-yellow-400 text-white px-3 py-1 rounded-md shadow hover:bg-yellow-300 transition duration-300">
                                Edit
                            </a>
                            <a href="{{ route('lecturer.questions.index', ['challenge_id' => $challenge->id]) }}"
                                class="bg-indigo-500 text-white px-3 py-1 rounded-md shadow hover:bg-indigo-400 transition duration-300">
                                Questions
                            </a>
                            <form action="{{ route('lecturer.challenges.destroy', $challenge->id) }}" method="POST"
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
    <div class="mt-4 flex justify-center">
        {{ $challenges->appends(['section_id' => request('section_id')])->links('pagination::tailwind') }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notification = document.getElementById('notification');

            if (notification) {
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 2000);
            }
        });
    </script>
@endsection
