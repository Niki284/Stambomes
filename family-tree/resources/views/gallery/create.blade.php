@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4 text-3xl font-bold text-center">Create Gallery</h1>
        <form action="{{ route('galleries.store', $person->id) }}" method="post" enctype="multipart/form-data" class="max-w-lg mx-auto">
            @csrf
            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-bold mb-2">Gallery Cover Image</label>
                <input type="file" name="image" id="image"class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Gallery</button>
        </form>

    </div>
@endsection
