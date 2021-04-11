<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;

class DistrictController extends Controller
{
    public function getByCityId(Request $request)
    {
        dd($request->all());
        $districts = District::where('province_id', $request->city_id)->pluck('name', 'id');

        return response()->json(['districts' => $districts]);
    }
}
