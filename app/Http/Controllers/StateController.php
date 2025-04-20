<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Add this import
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreStateRequest;

use App\Models\State; 


class StateController extends Controller
{
    public function index()
{
    $states = State::all();
    return view('states.statetable', compact('states'));
}

    
    public function destroy($id)
    {
        $existsInDetails = \DB::table('details')->where('state_id', $id)->exists();
        
        if ($existsInDetails) {
            return response()->json([
                'error' => 'Cannot delete state because it is associated with details.'
            ], 400); 
        }
    
        $state = State::find($id);
    
        if ($state) {
            $state->delete();
            return response()->json(['success' => 'State deleted successfully.']);
        }
    
        return response()->json([
            'error' => 'State not found.'
        ], 404); 
    }

    public function edit($id)
{
    $state = State::findOrFail($id);
    return view('states.editstate', compact('state'));
}
    
public function update(Request $request, $id)
{
    
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255|unique:states,name,' . $id,
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    $state = State::findOrFail($id);

    $state->update($request->only('name'));

    return response()->json([
        'message' => 'State updated successfully',
        'state' => $state
    ]);
}

public function create(Request $request)
{
    $country_id = $request->query('country_id');
    
    if (!$country_id) {
        return redirect()->route('countries.index')
            ->with('error', 'Country ID is required');
    }

    return view('states.editstate', compact('country_id'));
}

public function store(StoreStateRequest $request)
{
    $validatedData = $request->validated();


    $state = new State();
    $state->name = $request->input('name');
    $state->country_id = $request->input('country_id');
    $state->save();

    return redirect()->route('cities.create', ['state_id' => $state->id]);
}

}
