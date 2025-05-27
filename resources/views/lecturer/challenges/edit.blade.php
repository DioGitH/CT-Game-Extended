@extends('lecturer.layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Edit Challenge</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('lecturer.challenges.update', $challenge->id) }}" method="POST"
        class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        <!-- Pilih Section -->
        <div class="mb-4">
            <label for="section_id" class="block text-gray-700 font-bold mb-2">Select Section</label>
            <select name="section_id" id="section_id"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>
                <option value="" disabled>-- Select Section --</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}"
                        {{ old('section_id', $challenge->section_id) == $section->id ? 'selected' : '' }}>
                        {{ $section->order }} - {{ $section->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Title -->
        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $challenge->title) }}"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>
        </div>

        <!-- Total EXP (Disabled) -->
        <div class="mb-4">
            <label for="total_exp" class="block text-gray-700 font-bold mb-2">Total EXP</label>
            <input type="number" name="total_exp" id="total_exp" value="{{ old('total_exp', $challenge->total_exp) }}"
                class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-500 p-2" disabled>
            <p class="text-sm text-gray-500 mt-1">Akan dihitung otomatis berdasarkan total soal</p>
        </div>

        <!-- Total Score (Disabled) -->
        <div class="mb-4">
            <label for="total_score" class="block text-gray-700 font-bold mb-2">Total Score</label>
            <input type="number" name="total_score" id="total_score"
                value="{{ old('total_score', $challenge->total_score) }}"
                class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-500 p-2" disabled>
            <p class="text-sm text-gray-500 mt-1">Akan dihitung otomatis berdasarkan total soal</p>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Update Challenge</button>
    </form>
@endsection
