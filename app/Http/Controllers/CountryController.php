<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detail; 
use App\Models\Country; 
use App\Models\State; 
use App\Models\City;

class CountryController extends Controller
{ 
    public function index()
{
    $countries = Country::all();
    return view('countries.countrytable', compact('countries'));
}

public function store(Request $request)
    {
        $request->validate([
            'country' => 'required|string',
            'states' => 'required|array|max:5',
            'states.*.name' => 'required|string',
            'states.*.cities' => 'required|array|max:5',
            'states.*.cities.*.name' => 'required|string',
        ]);

        return response()->json(['message' => 'Country added successfully!']);
    }

    public function destroy($id)
{
    try {
        $country = Country::findOrFail($id);

        $existsInDetails = Detail::where('country_id', $id)->exists();

        if ($existsInDetails) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete country because it is associated with details.'
            ], 400);
        }

        $country->delete();

        return response()->json([
            'success' => true,
            'message' => 'Country deleted successfully.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Country not found or an error occurred.'
        ], 404);
    }
}

    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return response()->json($country);
    }

        public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => "required|string|max:255|unique:countries,name,{$id}",
        ]);

        $country = Country::findOrFail($id);
        $country->update($validated);

        return response()->json([
            'id' => $country->id,
            'name' => $country->name,
            'message' => 'Country updated successfully', // Optional success message
        ]);
    }


}
