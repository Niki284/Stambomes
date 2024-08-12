@extends('layouts.app')

@section('content')

<div class="container mx-auto mt-5">
    <h1 class="text-3xl font-bold mb-6">Countries</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('countries.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6 inline-block">Add New Country</a>

    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Name</th>
                <th class="py-2 px-4 border-b">Code</th>
                <th class="py-2 px-4 border-b">Flag</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($countries as $country)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $country->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $country->code }}</td>
                    <td class="py-2 px-4 border-b">
                        @if($country->country_flag)
                            <img src="{{ asset('storage/' . $country->country_flag) }}" alt="Flag" class="h-8 w-8">
                        @else
                            No Flag
                        @endif
                    </td>
                    <td class="py-2 px-4 border-b">
                        @if($country->country_land)
                            <img src="{{ asset('storage/' . $country->country_land) }}" alt="Flag" class="h-8 w-8">
                        @else
                            No Land
                        @endif
                    </td>
                    <td class="py-2 px-4 border-b">
                        <a href="{{ route('countries.edit', $country->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                        <form action="{{ route('countries.destroy', $country->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 ml-2">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
