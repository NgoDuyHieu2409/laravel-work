<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\City;

class MyProfileController extends Controller
{
    public function myProfile(Request $request)
    {
        $user = Auth::user();
        $city = City::pluck('name', 'id');

        return view('homes.users.index', compact([
            'user',
            'city'
        ]));
    }

    public function saveProfile(Request $request)
    {
        dd($request->all());   
    }
}
