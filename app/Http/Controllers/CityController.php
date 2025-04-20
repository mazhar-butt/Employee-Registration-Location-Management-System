<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::all();
        return view('cities.citytable', compact('cities'));
    }    

    
    public function destroy($id)
    {
        $existsInDetails = \DB::table('details')->where('city_id', $id)->exists();
    
        if ($existsInDetails) {
            return response()->json([
                'error' => 'Cannot delete city because it is associated with details.'
            ], 400); 
        }
    
        $city = City::find($id);
    
        if ($city) {
            $city->delete();
            return response()->json(['success' => 'City deleted successfully.']);
        }
    
        return response()->json([
            'error' => 'City not found.'
        ], 404); 
    }
    
public function edit($id)
{
    $city = City::findOrFail($id);
    return view('cities.editcity', compact('city'));
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => "required|string|max:255|unique:cities,name,{$id}",
    ]);

    $city = City::findOrFail($id);
    $city->update($validated);

    return response()->json([
        'id' => $city->id,
        'name' => $city->name,
        'message' => 'City updated successfully', 
    ]);
}





public function create(Request $request)
{
    $state_id = $request->query('state_id');
    return view('cities.editcity', ['state_id' => $state_id]);
}

public function storecity(Request $request)
{
$request->validate([
    'name' => 'required|string|max:255',
    'state_id' => 'required|exists:states,id'
]);

$state = State::findOrFail($request->state_id);

$city = new City();
$city->name = $request->input('name');
$city->state_id = $request->input('state_id');
$city->country_id = $state->country_id;  
$city->save();

return redirect()->route('details.index')
    ->with('success', 'Country, State and City added successfully.');
}

}
