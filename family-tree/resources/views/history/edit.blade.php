@extends('layouts.app')

@section('content')

<div class="container mt-5 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="mb-4 text-3xl font-bold">Edit History for {{ $person->first_name }} {{ $person->last_name }}</h1>

    <form action="{{ route('histories.update', $person->id) }}" method="POST" class="max-w-lg mx-auto">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
            <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $history->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="start_school" class="block text-gray-700 font-bold mb-2">Start School</label>
            <input type="date" name="start_school" id="start_school" value="{{ old('start_school', $history->start_school) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('start_school')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="end_school" class="block text-gray-700 font-bold mb-2">End School</label>
            <input type="date" name="end_school" id="end_school" value="{{ old('end_school', $history->end_school) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('end_school')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="start_spouse" class="block text-gray-700 font-bold mb-2">Start Spouse</label>
            <input type="date" name="start_spouse" id="start_spouse" value="{{ old('start_spouse', $history->start_spouse) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('start_spouse')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="end_spouse" class="block text-gray-700 font-bold mb-2">End Spouse</label>
            <input type="date" name="end_spouse" id="end_spouse" value="{{ old('end_spouse', $history->end_spouse) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('end_spouse')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save</button>
    </form>
</div>

@endsection
