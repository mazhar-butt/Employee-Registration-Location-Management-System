<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\StoreStateRequest;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\StoreDetailRequest;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Detail;

class EmployeeController extends Controller
{
    public function store(StoreCountryRequest $request)
    {
        \Log::info('Country store request received', $request->all());
        
        $validated = $request->validated();
        
        try {
            $country = Country::firstOrCreate(
                ['name' => $validated['country']],
                ['user_id' => $validated['user_id']]
            );
            
            \Log::info('Country created/found', ['country_id' => $country->id]);
            
            return response()->json([
                'success' => true,
                'country_id' => $country->id,
                'message' => 'Country stored successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error storing country', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error storing country'
            ], 500);
        }
    }
    
    
    public function storeState(StoreStateRequest $request)
    {
        try {
            $validated = $request->validated();
            
            $state = State::firstOrCreate(
                [
                    'name' => $validated['state'],
                    'country_id' => $validated['country_id']
                ],
                [
                    'user_id' => $validated['user_id']
                ]
            );
    
            return response()->json([
                'success' => true,
                'state_id' => $state->id,
                'message' => 'State stored successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error storing state', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error storing state'
            ], 500);
        }
    }
    
    
    public function storeCity(StoreCityRequest $request)
{
    $validated = $request->validated();
    
    $city = City::firstOrCreate(
        [
            'name' => $validated['city'],
            'user_id' => $validated['user_id'],
            'country_id' => $validated['country_id'],
            'state_id' => $validated['state_id'],
        ]
    );

    return redirect()->route('detail', [
        'city_id' => $city->id,
        'state_id' => $validated['state_id'],
        'country_id' => $validated['country_id'],
        'user_id' => $validated['user_id']
    ]);
}
    
    
public function detail(StoreDetailRequest $request) {
    try {
        $validated = $request->validated();

        $detail = Detail::create([
            'user_id' => $validated['user_id'],
            'dob' => $validated['dob'],
            'income' => $validated['income'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'country_id' => $validated['country_id'],
            'state_id' => $validated['state_id'],
            'city_id' => $validated['city_id'],
        ]);

        return response()->json(['message' => 'Details saved successfully!'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
    }
}

}
