@extends('lecturer.layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-gray-800">📘 Student Detail</h1>

    <!-- Grid Profil + Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <!-- Profil -->
        <div class="bg-white border border-gray-200 shadow-md rounded-xl p-6">
            <h2 class="text-xl font-bold text-blue-700 mb-4">👤 Profile</h2>
            <div class="flex items-center gap-4 mb-4">
                <img src="{{ $student->user->profile_photo ? asset('storage/' . $student->user->profile_photo) : asset('images/default-avatar.png') }}"
                    alt="Profile Photo" class="w-24 h-24 rounded-full object-cover border border-gray-300 shadow">
                <div>
                    <p class="text-gray-800"><strong>Name:</strong> {{ $student->user->name }}</p>
                    <p class="text-gray-800"><strong>NIM:</strong> {{ $student->nim }}</p>
                </div>
            </div>
            <p class="text-gray-700"><strong>Program Studi:</strong> {{ $student->prodi ?? '-' }}</p>
            <p class="text-gray-700"><strong>Semester:</strong> {{ $student->semester ?? '-' }}</p>
            <p class="text-gray-700"><strong>Class:</strong> {{ $student->class ?? '-' }}</p>
        </div>

        <!-- Statistik -->
        <div class="bg-white border border-gray-200 shadow-md rounded-xl p-6">
            <h2 class="text-xl font-bold text-yellow-600 mb-4">📈 Progress Info</h2>
            <ul class="space-y-2 text-gray-800">
                <li><strong>🔥 Streak:</strong> {{ $student->streak }}</li>
                <li><strong>⏱ Last Played:</strong>
                    {{ $student->last_played ? \Carbon\Carbon::parse($student->last_played)->translatedFormat('d F Y') : 'Never' }}
                    </p>
                </li>
                <li><strong>⭐ EXP:</strong> {{ $student->exp }}</li>
                <li><strong>📅 Weekly Score:</strong> {{ $student->weekly_score }}</li>
                <li><strong>🏆 Total Score:</strong> {{ $student->total_score }}</li>
                <li><strong>🎯 Current Challenge ID:</strong> {{ $student->current_challenge_id ?? 'None' }}</li>
                <li><strong>📚 Current Section ID:</strong> {{ $student->current_section_id ?? 'None' }}</li>
            </ul>
        </div>
    </div>

    <!-- Challenge Result Table -->
    <div class="bg-white border border-gray-200 shadow-md rounded-xl p-6">
        <h2 class="text-xl font-bold text-indigo-700 mb-4">📋 Challenge Results</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700 border border-gray-200 rounded-md overflow-hidden">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-6 py-3">Attempt</th>
                        <th class="px-6 py-3">Score</th>
                        <th class="px-6 py-3">EXP</th>
                        <th class="px-6 py-3">Correct</th>
                        <th class="px-6 py-3">Wrong</th>
                        <th class="px-6 py-3">Duration</th>
                        <th class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($results as $result)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">{{ $result->attempt_number }}</td>
                            <td class="px-6 py-4">{{ $result->total_score }}</td>
                            <td class="px-6 py-4">{{ $result->total_exp }}</td>
                            <td class="px-6 py-4">{{ $result->correct_answers }}</td>
                            <td class="px-6 py-4">{{ $result->wrong_answers }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $start = \Carbon\Carbon::parse($result->created_at);
                                    $end = \Carbon\Carbon::parse($result->ended_at ?? now());
                                    $duration = $start->diff($end)->format('%h hr %i min %s sec');

                                @endphp
                                {{ $duration }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('lecturer.students.detail_result', [
                                    'student' => $student->id,
                                    'challenge' => $result->challenge_id,
                                    'attempt' => $result->attempt_number,
                                ]) }}"
                                    class="bg-yellow-500 text-white px-3 py-1 rounded-md shadow hover:bg-yellow-400 transition duration-300">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No results found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back button -->
    <div class="mt-8">
        <a href="{{ route('lecturer.students.index') }}"
            class="inline-block bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold py-2 px-4 rounded shadow transition">
            ← Back to Student List
        </a>
    </div>
@endsection
