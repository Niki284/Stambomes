@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4 text-3xl font-bold text-center">Create Person</h1>

    <form action="{{ route('peoples.store') }}" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto">
        @csrf

        <div class="mb-4">
            <label for="first_name" class="block text-gray-700 font-bold mb-2">First Name</label>
            <input type="text" name="first_name" id="first_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="last_name" class="block text-gray-700 font-bold mb-2">Last Name</label>
            <input type="text" name="last_name" id="last_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="avatar" class="block text-gray-700 font-bold mb-2">Avatar or Photo</label>
            <input type="file" name="avatar" id="avatar" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="gender" class="block text-gray-700 font-bold mb-2">Gender</label>
            <select name="gender" id="gender" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="birth_date" class="block text-gray-700 font-bold mb-2">Birth Date</label>
            <input type="date" name="birth_date" id="birth_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="death_date" class="block text-gray-700 font-bold mb-2">Death Date</label>
            <input type="date" name="death_date" id="death_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="mother_id" class="block text-gray-700 font-bold mb-2">Mother</label>
            <select name="mother_id" id="mother_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">None</option>
                @foreach($people as $person)
                    <option value="{{ $person->id }}">{{ $person->first_name }} {{ $person->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="father_id" class="block text-gray-700 font-bold mb-2">Father</label>
            <select name="father_id" id="father_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">None</option>
                @foreach($people as $person)
                    <option value="{{ $person->id }}">{{ $person->first_name }} {{ $person->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="spouse_id" class="block text-gray-700 font-bold mb-2">Spouse</label>
            <select name="spouse_id" id="spouse_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">None</option>
                @foreach($people as $person)
                    <option value="{{ $person->id }}">{{ $person->first_name }} {{ $person->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="paternal_grandfather_id" class="block text-gray-700 font-bold mb-2">Paternal Grandfather</label>
            <select name="paternal_grandfather_id" id="paternal_grandfather_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">None</option>
                @foreach($people as $person)
                    <option value="{{ $person->id }}">{{ $person->first_name }} {{ $person->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="paternal_grandmother_id" class="block text-gray-700 font-bold mb-2">Paternal Grandmother</label>
            <select name="paternal_grandmother_id" id="paternal_grandmother_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">None</option>
                @foreach($people as $person)
                    <option value="{{ $person->id }}">{{ $person->first_name }} {{ $person->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="maternal_grandfather_id" class="block text-gray-700 font-bold mb-2">Maternal Grandfather</label>
            <select name="maternal_grandfather_id" id="maternal_grandfather_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">None</option>
                @foreach($people as $person)
                    <option value="{{ $person->id }}">{{ $person->first_name }} {{ $person->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="maternal_grandmother_id" class="block text-gray-700 font-bold mb-2">Maternal Grandmother</label>
            <select name="maternal_grandmother_id" id="maternal_grandmother_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">None</option>
                @foreach($people as $person)
                    <option value="{{ $person->id }}">{{ $person->first_name }} {{ $person->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="country_id" class="block text-gray-700 font-bold mb-2">Country</label>
            <select name="country_id" id="country_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">None</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save</button>
    </form>
</div>

@endsection
