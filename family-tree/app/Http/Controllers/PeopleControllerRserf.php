<?php

namespace App\Http\Controllers;

use App\Models\People;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    //
    public function index()
    {
        $people = People::with(['mother', 'father', 'spouse', 'children'])->get();
        return view('people.index', compact('people'));
    }

    public function create()
    {
        $people = People::all();
        return view('people.create', compact('people'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'death_date' => 'nullable|date',
            'mother_id' => 'nullable|exists:people,id',
            'father_id' => 'nullable|exists:people,id',
            'spouse_id' => 'nullable|exists:people,id',
        ]);

        People::create($data);

        return redirect()->route('peoples.index');
    }
}
