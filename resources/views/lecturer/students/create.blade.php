@extends('lecturer.layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Add New Student</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('lecturer.students.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>
        </div>

        <div class="mb-4">
            <label for="nim" class="block text-gray-700 font-bold mb-2">NIM</label>
            <input type="text" name="nim" id="nim" value="{{ old('nim') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>
        </div>

        <div class="mb-4">
            <label for="level" class="block text-gray-700 font-bold mb-2">Level</label>
            <input type="number" name="level" id="level" value="{{ old('level', 1) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>
        </div>

        <div class="mb-4">
            <label for="exp" class="block text-gray-700 font-bold mb-2">EXP</label>
            <input type="number" name="exp" id="exp" value="{{ old('exp', 0) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Add Student</button>
    </form>
@endsection