<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    //

    public function index()
    {
        $histories = History::all();
        return view('history.index', compact('histories'));
    }

    public function create($personId)
    {
        $person = People::findOrFail($personId);
        return view('history.create', compact('person'));
    }

    public function store(Request $request, $personId)
    {
        $person = People::findOrFail($personId);

        if (Auth::id() !== (int)$person->beheerder_id) {
            return redirect()->route('peoples.show', $personId)->with('error', 'You do not have permission to add history for this person.');
        }

        $request->validate([
            'description' => 'required',
            'start_school' => 'required|date',
            'end_school' => 'required|date|after:start_school',
            'start_spouse' => 'required|date',
            'end_spouse' => 'required|date|after:start_spouse',
        ]);

        History::create([
            'people_id' => $personId,
            'description' => $request->description,
            'start_school' => $request->start_school,
            'end_school' => $request->end_school,
            'start_spouse' => $request->start_spouse,
            'end_spouse' => $request->end_spouse,
        ]);

        return redirect()->route('peoples.show', $personId)
            ->with('success', 'History created successfully.');
    }

    public function edit($personId)
    {
        $person = People::findOrFail($personId);
        $history = $person->history;

        if (Auth::id() !== (int)$person->beheerder_id) {
            return redirect()->route('peoples.show', $personId)->with('error', 'You do not have permission to edit history for this person.');
        }

        return view('history.edit', compact('history', 'person'));
    }

    public function update(Request $request, $personId)
    {
        $person = People::findOrFail($personId);
        $history = $person->history;

        if (Auth::id() !== (int)$person->beheerder_id) {
            return redirect()->route('peoples.show', $personId)->with('error', 'You do not have permission to update history for this person.');
        }

        $request->validate([
            'description' => 'required',
            'start_school' => 'required|date',
            'end_school' => 'required|date|after:start_school',
            'start_spouse' => 'required|date',
            'end_spouse' => 'required|date|after:start_spouse',
        ]);

        $history->update($request->all());

        return redirect()->route('peoples.show', $personId)
            ->with('success', 'History updated successfully');
    }

    public function destroy($personId)
    {
        $person = People::findOrFail($personId);
        $history = $person->history;

        if (Auth::id() !== (int)$person->beheerder_id) {
            return redirect()->route('peoples.show', $personId)->with('error', 'You do not have permission to delete history for this person.');
        }

        $history->delete();

        return redirect()->route('peoples.show', $personId)
            ->with('success', 'History deleted successfully');
    }
}
