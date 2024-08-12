@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4 text-3xl font-bold text-center">Create History for {{ $person->first_name }} {{ $person->last_name }}</h1>

    <form action="{{ route('histories.store', $person->id) }}" method="POST" class="max-w-lg mx-auto">
        @csrf

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
            <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
        </div>

        <div class="mb-4">
            <label for="start_school" class="block text-gray-700 font-bold mb-2">Start School Date</label>
            <input type="date" name="start_school" id="start_school" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="end_school" class="block text-gray-700 font-bold mb-2">End School Date</label>
            <input type="date" name="end_school" id="end_school" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="start_spouse" class="block text-gray-700 font-bold mb-2">Start Spouse Date</label>
            <input type="date" name="start_spouse" id="start_spouse" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="end_spouse" class="block text-gray-700 font-bold mb-2">End Spouse Date</label>
            <input type="date" name="end_spouse" id="end_spouse" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save</button>
    </form>
</div>

@endsection
