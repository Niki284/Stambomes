@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5">
    <h1 class="text-3xl font-bold mb-6">Edit Country</h1>

    <form action="{{ route('countries.update', $country->id) }}" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Country Name</label>
            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('name', $country->name) }}" required>
        </div>

        <div class="mb-4">
            <label for="code" class="block text-gray-700 font-bold mb-2">Country Code</label>
            <input type="text" name="code" id="code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('code', $country->code) }}" required>
        </div>

        <div class="mb-4">
            <label for="country_flag" class="block text-gray-700 font-bold mb-2">Country Flag</label>
            @if($country->country_flag)
                <img src="{{ asset('storage/' . $country->country_flag) }}" alt="Country Flag" class="w-24 h-16 mb-2">
            @endif
            <input type="file" name="country_flag" id="country_flag" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="country_land" class="block text-gray-700 font-bold mb-2">Country Land</label>
            @if($country->country_land)
                <img src="{{ asset('storage/' . $country->country_land) }}" alt="Country Land" class="w-24 h-16 mb-2">
            @endif
            <input type="file" name="country_land" id="country_land" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save</button>
    </form>
</div>
@endsection
