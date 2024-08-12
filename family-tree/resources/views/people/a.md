@extends('layouts.app')

@section('content')

<div class="container mt-5 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="mb-4 text-4xl font-bold text-gray-800">{{ $person->first_name }} {{ $person->last_name }}</h1>

    <div class="flex justify-center mb-6">
    <a href="{{ route('peoples.index') }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg shadow hover:bg-orange-700 transition-transform transform hover:scale-105 mx-2">Tree</a>
        <a href="{{ route('peoples.create') }}" class="bg-cyan-600 text-white px-4 py-2 rounded-lg shadow hover:bg-cyan-700 transition-transform transform hover:scale-105 mx-2">Add User</a>
        <a href="{{ route('peoples.search') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-700 transition-transform transform hover:scale-105 mx-2">Search Peoples</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 bg-white p-6 rounded-lg shadow-lg">
        <!-- Information Section -->
        <div class="info lg:col-span-1">
            <h2 class="text-3xl font-semibold text-gray-800 mb-4">{{ $person->first_name }} {{ $person->last_name }}</h2>

            @if($person->avatar)
                <img src="{{ asset('storage/' . $person->avatar) }}" alt="Avatar" class="w-32 h-32 rounded-full mb-4 transition-transform transform hover:scale-110">
            @endif

            <!-- Gender Section -->
            <p class="text-gray-600 flex items-center">
                Gender: 
                <span class="font-medium ml-2">{{ ucfirst($person->gender) }}</span>
                @if(strtolower($person->gender) == 'female')
                    <svg class="ml-2 w-5 h-5 text-pink-500 transition-transform transform hover:scale-125" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a7 7 0 00-7 7v4H4a2 2 0 000 4h1v3a2 2 0 002 2h10a2 2 0 002-2v-3h1a2 2 0 000-4h-1V9a7 7 0 00-7-7zM5 18v-1h14v1H5z" />
                    </svg>
                @elseif(strtolower($person->gender) == 'male')
                    <svg class="ml-2 w-5 h-5 text-blue-500 transition-transform transform hover:scale-125" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a5 5 0 10-10 0 5 5 0 0010 0zm4 2h-3.09a7.003 7.003 0 01-13.82 0H2a9 9 0 1017 0z" />
                    </svg>
                @endif
            </p>

            <p class="text-gray-600 mt-2">Birth Date: <span class="font-medium">{{ $person->birth_date }}</span></p>
            <p class="text-gray-600 mt-2">Death Date: <span class="font-medium">{{ $person->death_date ?? 'N/A' }}</span></p>

            <!-- Countries Section -->
            @if($person->countries->isNotEmpty())
                <div class="mt-6">
                    <h3 class="text-xl font-semibold text-gray-700">Countries</h3>
                    <ul class="list-disc ml-6 mt-2">
                        @foreach($person->countries as $country)
                            <li class="flex items-center mt-2">
                                @if($country->country_flag)
                                    <img src="{{ asset('storage/' . $country->country_flag) }}" alt="{{ $country->name }} Flag" class="w-6 h-4 mr-2 transition-transform transform hover:scale-110">
                                @endif
                                {{ $country->name }}
                            </li>
                            <li class="flex items-center mt-2">
                                @if($country->country_land)
                                    <img src="{{ asset('storage/' . $country->country_land) }}" alt="{{ $country->name }} Land" class="w-28 h-28 mr-2 transition-transform transform hover:scale-110">
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Family Section -->
            <div class="mt-6">
                <h3 class="text-xl font-semibold text-gray-700">Parents</h3>
                <ul class="list-disc ml-6 mt-2">
                    @if($person->mother)
                        <li>{{ $person->mother->first_name }} {{ $person->mother->last_name }}</li>
                    @endif
                    @if($person->father)
                        <li>{{ $person->father->first_name }} {{ $person->father->last_name }}</li>
                    @endif
                </ul>
            </div>

            <div class="mt-6">
                <h3 class="text-xl font-semibold text-gray-700">Spouse</h3>
                <p class="mt-2 text-gray-600">
                    @if($person->spouse)
                        {{ $person->spouse->first_name }} {{ $person->spouse->last_name }}
                    @else
                        No spouse
                    @endif
                </p>
            </div>

            <div class="mt-6">
                <h3 class="text-xl font-semibold text-gray-700">Children</h3>
                <ul class="list-disc ml-6 mt-2">
                    @forelse($person->children as $child)
                        <li>
                            <a href="{{ route('peoples.show', $child->id) }}" class="text-cyan-600 hover:underline transition-colors hover:text-cyan-800">
                                {{ $child->first_name }} {{ $child->last_name }}
                            </a>
                        </li>
                    @empty
                        <li>No children</li>
                    @endforelse
                </ul>
            </div>

            <div class="mt-6">
                <h3 class="text-xl font-semibold text-gray-700">Grandparents</h3>
                <ul class="list-disc ml-6 mt-2">
                    @if($maternalGrandfather)
                        <li>Maternal Grandfather: {{ $maternalGrandfather->first_name }} {{ $maternalGrandfather->last_name }}</li>
                    @endif
                    @if($maternalGrandmother)
                        <li>Maternal Grandmother: {{ $maternalGrandmother->first_name }} {{ $maternalGrandmother->last_name }}</li>
                    @endif
                    @if($paternalGrandfather)
                        <li>Paternal Grandfather: {{ $paternalGrandfather->first_name }} {{ $paternalGrandfather->last_name }}</li>
                    @endif
                    @if($paternalGrandmother)
                        <li>Paternal Grandmother: {{ $paternalGrandmother->first_name }} {{ $paternalGrandmother->last_name }}</li>
                    @endif
                </ul>
            </div>

            @if($isBeheerder)
                <div class="mt-6 flex space-x-2">
                    <a href="{{ route('peoples.edit', $person->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition-transform transform hover:scale-105">Edit</a>
                    <form action="{{ route('peoples.destroy', $person->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this person?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700 transition-transform transform hover:scale-105">Delete</button>
                    </form>
                </div>
            @endif
        </div>

        <!-- History and Gallery Section -->
        <div class="history-and-gallery lg:col-span-2">
            <div class="history mb-8">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4">History</h2>

                @if($isBeheerder)
                    <div class="flex space-x-2 mb-4">
                        <a href="{{ route('histories.create', $person->id) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition-transform transform hover:scale-105">Create History</a>
                        @if ($person->history)
                            <a href="{{ route('histories.edit', $person->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-700 transition-transform transform hover:scale-105">Update History</a>
                            <form action="{{ route('histories.destroy', $person->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this history?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700 transition-transform transform hover:scale-105">Delete History</button>
                            </form>
                        @endif
                    </div>
                @endif

                <h3 class="text-xl font-semibold text-gray-700">History Details</h3>
                @if ($person->history)
                    <p class="text-gray-600 mt-2">{{ $person->history->description }}</p>
                    <ul class="list-disc ml-6 mt-2">
                        <li>Start School: {{ $person->history->start_school }}</li>
                        <li>End School: {{ $person->history->end_school }}</li>
                        <li>Start Spouse: {{ $person->history->start_spouse }}</li>
                        <li>End Spouse: {{ $person->history->end_spouse }}</li>
                    </ul>
                @else
                    <p class="text-gray-600 mt-2">No history available.</p>
                @endif
            </div>

            <div class="gallery">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4">Gallery</h2>

                @if($isBeheerder)
                    <div class="flex space-x-2 mb-4">
                        <a href="{{ route('galleries.create', $person->id) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition-transform transform hover:scale-105">Create Gallery</a>
                        @if ($person->gallery)
                            <a href="{{ route('galleries.edit', $person->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-700 transition-transform transform hover:scale-105">Update Gallery</a>
                            <form action="{{ route('galleries.destroy', $person->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this gallery?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700 transition-transform transform hover:scale-105">Delete Gallery</button>
                            </form>
                        @endif
                    </div>
                @endif

                <ul class="grid grid-cols-2 gap-4">
                    @if($galleries->isNotEmpty())
                        @foreach($galleries as $gallery)
                            <li class="relative transition-transform transform hover:scale-105">
                                <img src="{{ asset('storage/' . $gallery->image) }}" alt="Gallery Image" class="w-full h-auto rounded-lg shadow-md">
                                <form action="{{ route('galleries.destroy', ['personId' => $person->id, 'galleryId' => $gallery->id]) }}" method="POST" class="absolute top-2 right-2" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded-lg shadow hover:bg-red-700 transition-transform transform hover:scale-105 text-sm">Delete</button>
                                </form>
                            </li>
                        @endforeach
                    @else
                        <p>No gallery available.</p>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
