<?php
namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // Add this import for DB


class LocationController extends Controller
{

    public function getStates($country_id)
{
    try {
        $states = State::where('country_id', $country_id)->get();
        return response()->json($states);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch states'], 500);
    }
}


    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)->get();

        return response()->json([
            'cities' => $cities
        ]);
    }

    public function store(Request $request)
{
    try {
        DB::beginTransaction();

        // Set the locale based on the request or user preference
        $locale = $request->get('locale', 'en'); // Default to English
        app()->setLocale($locale);

        $validated = $request->validate([
            'country' => 'required|string|max:255|min:3', 
            'states' => 'required|array|max:5',
            'states.*.name' => 'required|string|max:255|min:3', 
            'states.*.cities' => 'array|max:5',
            'states.*.cities.*.name' => 'required|string|max:255|min:3', 
        ]);

        $country = Country::create([
            'name' => $request->country
        ]);

        foreach ($request->states as $stateData) {
            $state = $country->states()->create([
                'name' => $stateData['name'],
                'country_id' => $country->id // Explicitly set country_id
            ]);

            if (!empty($stateData['cities'])) {
                foreach ($stateData['cities'] as $cityData) {
                    $state->cities()->create([
                        'name' => $cityData['name'],
                        'state_id' => $state->id,
                        'country_id' => $country->id // Explicitly set country_id
                    ]);
                }
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Locations saved successfully',
            'data' => $country->load('states.cities')
        ], 201);

    }  catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollback();

        // Construct the error message based on the selected locale
        $errorMessages = [];
        foreach ($e->errors() as $field => $messages) {
            foreach ($messages as $message) {
                $errorMessages[] = $message; // Collect each message
            }
        }

        $message = __('validation.validation_failed'); // This will automatically use the correct language based on the locale set

        return response()->json([
            'success' => false,
            'message' => $message, // Dynamic message based on locale
            'errors' => $errorMessages // Collect all error messages
        ], 422);

    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('Location save error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error saving locations: ' . $e->getMessage()
        ], 500);
    }
}

    public function index()
    {
        return view('countries.editcountry');
    }
}
