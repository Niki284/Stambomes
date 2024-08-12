@extends('layouts.app')

@section('content')

<div class="container mt-5 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="mb-4 text-3xl font-bold">Family Tree</h1>
    <div class="d-flex justify-content-center mb-4">
        <a href="{{ route('peoples.create') }}" class="btn btn-primary text-cyan-600 mx-2">Add User</a>
        <a href="{{ route('peoples.search') }}" class="btn btn-secondary mx-2">Search Peoples</a>
    </div>

    <div class="info bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold">{{ $person->first_name }} {{ $person->last_name }}</h2>

        @if($person->avatar)
            <img src="{{ asset('storage/' . $person->avatar) }}" alt="Avatar" class="w-24 h-24 rounded-full mt-4">
        @endif

        <p class="mt-2">Gender: {{ ucfirst($person->gender) }}</p>
        <p class="mt-2">Birth Date: {{ $person->birth_date }}</p>
        <p class="mt-2">Death Date: {{ $person->death_date ?? 'N/A' }}</p>

        @if($person->countries->isNotEmpty())
            <div class="mt-4">
                <h3 class="text-lg font-semibold">Countries</h3>
                <ul class="list-disc ml-5 mt-2">
                    @foreach($person->countries as $country)
                        <li class="flex items-center">
                            @if($country->country_flag)
                                <img src="{{ asset('storage/' . $country->country_flag) }}" alt="{{ $country->name }} Flag" class="w-6 h-4 mr-2">
                            @endif
                            {{ $country->name }}
                        </li>
                        <li class="flex items-center">
                            @if($country->country_land)
                                <img src="{{ asset('storage/' . $country->country_land) }}" alt="{{ $country->name }} Land" class="w-28 h-28 mr-2">
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-4">
            <h3 class="text-lg font-semibold">Parents</h3>
            <ul class="list-disc ml-5">
                @if($person->mother)
                    <li>{{ $person->mother->first_name }} {{ $person->mother->last_name }}</li>
                @endif
                @if($person->father)
                    <li>{{ $person->father->first_name }} {{ $person->father->last_name }}</li>
                @endif
            </ul>
        </div>

        <div class="mt-4">
            <h3 class="text-lg font-semibold">Spouse</h3>
            @if($person->spouse)
                <p>{{ $person->spouse->first_name }} {{ $person->spouse->last_name }}</p>
            @else
                <p>No spouse</p>
            @endif
        </div>

        <div class="mt-4">
            <h3 class="text-lg font-semibold">Children</h3>
            <ul class="list-disc ml-5">
                @forelse($person->children as $child)
                    <li>
                        <a href="{{ route('peoples.show', $child->id) }}" class="text-blue-500 hover:underline">
                            {{ $child->first_name }} {{ $child->last_name }}
                        </a>
                    </li>
                @empty
                    <li>No children</li>
                @endforelse
            </ul>
        </div>

        <div class="mt-4">
            <h3 class="text-lg font-semibold">Grandparents</h3>
            <ul class="list-disc ml-5">
                @if($maternalGrandfather)
                    <li>Maternal Grandfather: {{ $maternalGrandfather->first_name }} {{ $maternalGrandfather->last_name }}</li>
                @endif
               
            </ul>
        </div>

        @if($isBeheerder)
            <div class="mt-4 flex space-x-2">
                <a href="{{ route('peoples.edit', $person->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>

                <form action="{{ route('peoples.destroy', $person->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this person?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
                </form>
            </div>
        @endif

        <div class="history">
        <h2 class="text-2xl font-semibold">History</h2>
        <a href="">Create History</a>
        <a href="">Update History</a>
        <a href="">Delete History</a>

        <h3 class="text-lg font-semibold">Histories</h3>
        <p>description</p>
        <ul>
            <li>start_school</li>
            <li> end_school</li>
            <li>start_spouse</li>
            <li>end_spouse</li>
        </ul>
    </div>
    </div>
</div>


@endsection
