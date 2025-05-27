@extends('lecturer.layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Edit Section</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('lecturer.sections.update', $section->id) }}" method="POST"
        class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="order" class="block text-gray-700 font-bold mb-2">Order</label>
            <input type="number" name="order" id="order" value="{{ old('order', $section->order) }}"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>
        </div>

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $section->name) }}"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 p-2" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Update Section</button>
    </form>
@endsection
