<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CountryController extends Controller
{
    //
    public function index()
    {
        $countries = Country::all();
        return view('countries.index', compact('countries'));
    }
    public function create()
    {
        return view('countries.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:countries,name',
            'code' => 'required|string|max:2|unique:countries,code',
            'country_flag' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1500',
            'country_land' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1500',
        ]);

        if ($request->hasFile('country_flag')) {
            $flag = $request->file('country_flag');
            $filename = time() . '.' . $flag->getClientOriginalExtension();
            $path = $flag->storeAs('country_flags', $filename, 'public');
            $data['country_flag'] = $path;
        }

        if ($request->hasFile('country_land')) {
            $land = $request->file('country_land');
            $filename = time() . '.' . $land->getClientOriginalExtension();
            $path = $land->storeAs('country_lands', $filename, 'public');
            $data['country_land'] = $path;
        }

        Country::create($data);

        return redirect()->route('countries.index')->with('success', 'Country created successfully.');
    }

    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('countries.edit', compact('country'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,' . $id,
            'code' => 'required|string|max:2|unique:countries,code,' . $id,
            'country_flag' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1500',
            'country_land' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1500',

        ]);

        $country = Country::findOrFail($id);
        if ($request->hasFile('country_flag')) {
            if ($country->country_flag) {
                Storage::disk('public')->delete($country->country_flag);
            }
            $flag = $request->file('country_flag');
            $filename = time() . '.' . $flag->getClientOriginalExtension();
            $path = $flag->storeAs('country_flags', $filename, 'public');
            $data['country_flag'] = $path;
        }
    
        if ($request->hasFile('country_land')) {
            if ($country->country_land) {
                Storage::disk('public')->delete($country->country_land);
            }
            $land = $request->file('country_land');
            $filename = time() . '.' . $land->getClientOriginalExtension();
            $path = $land->storeAs('country_lands', $filename, 'public');
            $data['country_land'] = $path;
        }
        $country->update($data);

        return redirect()->route('countries.index')->with('success', 'Country updated successfully.');
    }

    public function destroy($id)
    {
        $country = Country::findOrFail($id);

        if ($country->country_flag) {
            Storage::disk('public')->delete($country->country_flag);
        }
    
        if ($country->country_land) {
            Storage::disk('public')->delete($country->country_land);
        }

        $country->delete();

        return redirect()->route('countries.index')->with('success', 'Country deleted successfully.');
    }
}
