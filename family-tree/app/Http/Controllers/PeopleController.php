<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PeopleController extends Controller
{
    //
    public function index()
    {
        // old code
        // $userId = Auth::id();
        // return view('people.index');

        // new code
        $userId = Auth::id();

        // Найти первого пользователя с текущим beheerder_id
        $firstUser = People::where('beheerder_id', $userId)->first();

        // Если такой пользователь найден, передаем его ID, иначе передаем null
        $currentUserId = $firstUser ? $firstUser->id : null;

        return view('people.index', ['currentUserId' => $currentUserId]);
    }

    public function getUsers()
    {
        $userId = Auth::id();

        $users = People::with(['mother', 'father', 'spouse', 'children', 'paternalGrandfather', 'paternalGrandmother', 'maternalGrandfather', 'maternalGrandmother'])
            ->where('beheerder_id', $userId) // Фильтруем по текущему пользователю
            ->get();

        return response()->json($users);
    }

    public function search(Request $request)
    {
        $query = $request->input('query'); // Поиск по имени или фамилии
        $countryId = $request->input('country'); // ID страны

        $people = People::query()
            ->when($query, function ($q) use ($query) {
                $q->where('first_name', 'like', '%' . $query . '%')
                    ->orWhere('last_name', 'like', '%' . $query . '%');
            })
            ->when($countryId, function ($q) use ($countryId) {
                $q->whereHas('countries', function ($countryQuery) use ($countryId) {
                    $countryQuery->where('countries.id', $countryId);
                });
            })
            ->with('countries')
            ->get();

        return view('people.search', compact('people'));
    }

    public function create()
    {
        // $userId = Auth::id();
        // $people = People::all();
        // $countries = Country::all(); // Get all countries for the dropdown
        // return view('people.create', compact('people', 'countries'));
        $userId = Auth::id();
        $people = People::where('beheerder_id', $userId)->get();
        $countries = Country::all(); // Get all countries for the dropdown
        return view('people.create', compact('people', 'countries'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1500',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'death_date' => 'nullable|date',
            'mother_id' => 'nullable|exists:people,id',
            'father_id' => 'nullable|exists:people,id',
            'spouse_id' => 'nullable|exists:people,id',
            'paternal_grandfather_id' => 'nullable|exists:people,id',
            'paternal_grandmother_id' => 'nullable|exists:people,id',
            'maternal_grandfather_id' => 'nullable|exists:people,id',
            'maternal_grandmother_id' => 'nullable|exists:people,id',
            'is_alive' => 'nullable|boolean',
            'country_id' => 'nullable|exists:countries,id', // Validate country_id
        ]);

        $data['beheerder_id'] = Auth::id(); // Устанавливаем текущего пользователя как управляющего

        // Если дата смерти указана, устанавливаем is_alive в false
        if (!empty($data['death_date'])) {
            $data['is_alive'] = false;
        } else {
            // Если is_alive не указано, устанавливаем по умолчанию true
            $data['is_alive'] = $data['is_alive'] ?? true;
        }

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $path = $avatar->storeAs('avatars', $filename, 'public');
            $data['avatar'] = $path;
        } else {
            $data['avatar'] = 'avatars/user.png';
        }

        $person = People::create($data);

        if ($request->filled('country_id')) {
            $person->countries()->attach($request->input('country_id'));
        }

        return redirect()->route('peoples.index');
    }

    public function show($id)
    {
        $person = People::with('countries', 'mother', 'father', 'children', 'history', 'galleries')->findOrFail($id);

        $isBeheerder = Auth::id() === (int)$person->beheerder_id;
        $histories = $person->history;
        $galleries = $person->galleries;
        $maternalGrandfather = $person->mother ? $person->mother->father : null;
        $maternalGrandmother = $person->mother ? $person->mother->mother : null;
        $paternalGrandfather = $person->father ? $person->father->father : null;
        $paternalGrandmother = $person->father ? $person->father->mother : null;

        return view('people.show', compact('person', 'isBeheerder', 'maternalGrandfather', 'maternalGrandmother', 'paternalGrandfather', 'paternalGrandmother', 'histories', 'galleries'));
    }

    public function edit($id)
    {
        $people = People::findOrFail($id);

        if (Auth::id() !== (int)$people->beheerder_id) {
            return redirect()->route('peoples.show', $id)->with('error', 'You do not have permission to edit this person.');
        }

        $userId = Auth::id();
        $peopleList = People::where('beheerder_id', $userId)->get(); // Get all people for the dropdowns
        $countries = Country::all(); // Get all countries for the dropdown
        return view('people.edit', compact('people', 'peopleList', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $people = People::findOrFail($id);

        if (Auth::id() !== (int)$people->beheerder_id) {
            return redirect()->route('peoples.show', $id)->with('error', 'You do not have permission to update this person.');
        }

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'death_date' => 'nullable|date',
            'mother_id' => 'nullable|exists:people,id',
            'father_id' => 'nullable|exists:people,id',
            'spouse_id' => 'nullable|exists:people,id',
            'paternal_grandfather_id' => 'nullable|exists:people,id',
            'paternal_grandmother_id' => 'nullable|exists:people,id',
            'maternal_grandfather_id' => 'nullable|exists:people,id',
            'maternal_grandmother_id' => 'nullable|exists:people,id',
            'country_id' => 'nullable|exists:countries,id', // Validate country_id
        ]);

        if ($request->hasFile('avatar')) {
            if ($people->avatar && $people->avatar !== 'avatars/user.png') {
                Storage::disk('public')->delete($people->avatar);
            }
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $path = $avatar->storeAs('avatars', $filename, 'public');
            $data['avatar'] = $path;
        }
        // if ($request->hasFile('avatar')) {
        //     if ($people->avatar) {
        //         Storage::disk('public')->delete($people->avatar);
        //     }
        //     $avatar = $request->file('avatar');
        //     $filename = time() . '.' . $avatar->getClientOriginalExtension();
        //     $path = $avatar->storeAs('avatars', $filename, 'public');
        //     $data['avatar'] = $path;
        // }

        $people->update($data);

        // Update country relationship
        $people->countries()->sync($request->input('country_id'));

        return redirect()->route('peoples.show', $id)->with('success', 'Person updated successfully.');
    }

    public function destroy($id)
    {
        $person = People::findOrFail($id);

        $person->countries()->detach(); // Удаляем связи с странами



        $person->delete();

        // Перенаправление после удаления
        return redirect()->route('peoples.index')->with('success', 'Person deleted successfully.');
    }
}
