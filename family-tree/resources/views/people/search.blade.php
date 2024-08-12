@extends('layouts.app')

@section('content')
<div class="container mt-5 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between	">
        <h1 class="mb-6 text-4xl font-bold text-gray-800">Search People</h1>
        <div class="flex justify-center mb-6">
            <a href="{{ route('peoples.index') }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg shadow hover:bg-orange-700 transition-transform transform hover:scale-105 mx-2">Tree</a>
            <a href="{{ route('peoples.create') }}" class="bg-cyan-600 text-white px-4 py-2 rounded-lg shadow hover:bg-cyan-700 transition-transform transform hover:scale-105 mx-2">Add User</a>
            <a href="{{ route('peoples.search') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-700 transition-transform transform hover:scale-105 mx-2">Search Peoples</a>
        </div>
    </div>

<!-- Поисковая форма -->
<form method="GET" action="{{ route('peoples.search') }}" class="mb-8 p-6 bg-gradient-to-r from-gray-600 to-gray-700 rounded-xl shadow-xl">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
        <div class="col-span-2">
            <label for="query" class="block text-sm font-medium text-white mb-1">Search by Name or Surname</label>
            <input type="text" name="query" id="query" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500" placeholder="Enter name or surname..." value="{{ request('query') }}">
        </div>

        <div>
            <label for="country" class="block text-sm font-medium text-white mb-1">Country</label>
            <select name="country" id="country" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500">
                <option value="">All Countries</option>
                @foreach($people->pluck('countries')->flatten()->unique('id') as $country)
                <option value="{{ $country->id }}" {{ request('country') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-3">
            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-700 transform hover:scale-105 transition-transform">Search</button>
        </div>
    </div>
</form>


    <!-- Результаты поиска -->
    @if($people->isEmpty())
    <p class="text-gray-500">No people found.</p>
    @else
    <h2 class="text-2xl font-bold mb-4">Search Results</h2>
    <ul class="space-y-4">
        @foreach($people as $person)
        <li class="p-4 bg-white rounded-lg shadow-md flex items-center">
            <a href="{{ route('peoples.show', $person->id) }}" class="flex items-center space-x-4">
                @if($person->avatar)
                <img src="{{ asset('storage/' . $person->avatar) }}" alt="Avatar" class="w-24 h-24 rounded-full">
                @endif
                <div>
                    <strong class="text-xl font-semibold">{{ $person->first_name }} {{ $person->last_name }}</strong>
                    <ul class="flex space-x-2 mt-2">
                        @foreach($person->countries as $country)
                        <li class="flex items-center space-x-1">
                            <img src="{{ asset('storage/' . $country->country_flag) }}" alt="{{ $country->name }} flag" class="w-8 h-5 rounded">
                            <span class="text-gray-600">{{ $country->name }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </a>
        </li>
        @endforeach
    </ul>
    @endif
</div>
@endsection