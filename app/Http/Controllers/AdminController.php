<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Detail;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class AdminController extends Controller
{

    public function showEmployeeDetails()
{

    $details = Detail::with(['country', 'state', 'city'])->get();

    return view('app', compact('details'));
}
public function destroy($id)
{
    try {
        $detail = Detail::findOrFail($id);
        $detail->delete();
        return response()->json(['message' => 'Employee deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete employee'], 500);
    }
}

public function edit($id)
{
    $detail = Detail::findOrFail($id);

    $countries = Country::all();
    $states = State::all();
    $cities = City::all();

    return view('app', compact('detail', 'countries', 'states', 'cities', 'id'));
}

public function update(Request $request, $id)
{
    try {
        $detail = Detail::findOrFail($id);
        
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username,'.$detail->user_id.',id', // Keep this line as is
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'dob' => 'required|date',
            'income' => 'required|numeric',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:255|min:3', // Change min:2 to min:3
            'phone' => 'required|string|max:17|min:3'
        ]);

        // Update user's username if changed
        if ($detail->user) {
            $detail->user->update(['username' => $validated['username']]);
        }

        // Remove username from validated data as it's not in details table
        unset($validated['username']);

        // Update the detail record
        $detail->update($validated);

        return response()->json([
            'message' => 'Employee details updated successfully',
            'data' => $detail
        ]);
    } catch (\Exception $e) {
        // Return validation errors with proper format
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        return response()->json([
            'message' => 'Error updating employee details',
            'error' => $e->getMessage()
        ], 422);
    }
}

public function show($id)
{
    $detail = Detail::with(['user', 'country', 'state', 'city'])->findOrFail($id);
    return response()->json($detail);
}

public function getCountries()
{
    $countries = Country::all();
    return response()->json($countries);
}

public function getStates($countryId)
{
    $states = State::where('country_id', $countryId)->get();
    return response()->json($states);
}

public function getCities($stateId)
{
    \Log::info('Fetching cities for state ID: ' . $stateId);
    $cities = City::where('state_id', $stateId)->get();
    \Log::info('Found cities: ' . $cities->count());
    return response()->json($cities);
}

public function index(Request $request)
{
    $sortOrder = $request->get('sort', 'asc'); 
    $details = Detail::with(['user', 'country', 'state', 'city'])
        ->orderBy('id', $sortOrder)
        ->get();

    return response()->json($details);
}
}