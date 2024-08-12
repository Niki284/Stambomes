@extends('layouts.app')

@section('content')

<div class="container mx-auto mt-5">
    <h1 class="text-3xl font-bold mb-6">Create Country</h1>

    <form action="{{ route('countries.store') }}" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Country Name</label>
            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('name') }}" required>
        </div>

        <div class="mb-4">
            <label for="code" class="block text-gray-700 font-bold mb-2">Country Code</label>
            <input type="text" name="code" id="code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('code') }}" required>
        </div>

        <div class="mb-4">
            <label for="country_flag" class="block text-gray-700 font-bold mb-2">Country Flag</label>
            <input type="file" name="country_flag" id="country_flag" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="country_land" class="block text-gray-700 font-bold mb-2">Country Land</label>
            <input type="file" name="country_land" id="country_land" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save</button>
    </form>
</div>

@endsection
