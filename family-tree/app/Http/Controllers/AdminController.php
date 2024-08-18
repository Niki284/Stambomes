<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function index()
    {
        $this->authorizeAdmin();
        $users = User::all();
        $countries = Country::all();
        return view('admin.index', compact('users', 'countries'));
        // return view('admin.index', ['user' => auth()->user()]);
    }

    public function manageCountries()
    {
        $this->authorizeAdmin();
        $countries = Country::all();
        return view('admin.countries', compact('countries'));
    }

    public function manageUsers()
    {
        $this->authorizeAdmin();
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    private function authorizeAdmin()
    {
        if (auth()->user()->is_admin !== 1) {
            return redirect('/')->with('error', 'You are not authorized to access this page.');
        }
    }

    public function destroyUser($id)
    {
        $this->authorizeAdmin();
        $user = User::find($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
