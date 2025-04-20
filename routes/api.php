<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CityController;
use App\Models\State;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/location', [LocationController::class, 'store']);

Route::get('/details/{id}', [AdminController::class, 'show']);
Route::get('/countries', [AdminController::class, 'getCountries']);
Route::get('/states/{countryId}', [AdminController::class, 'getStates']);
Route::get('/cities/{stateId}', [AdminController::class, 'getCities']);
Route::apiresource('details', AdminController::class)
    ->only(['index', 'edit', 'update', 'destroy', ])
    ->names([
        'index' => 'details.index',
        'edit' => 'detail.edit',
        'update' => 'detail.update',
        'destroy' => 'detail.destroy'
]);

    Route::post('/store-country', [EmployeeController::class, 'store'])->name('country.store');
    Route::post('/store-state', [EmployeeController::class, 'storeState'])->name('state.store');
    Route::post('/store-city', [EmployeeController::class, 'storeCity'])->name('city.store');
    Route::post('/store-detail', [EmployeeController::class, 'detail'])->name('detail.store');
    

    
Route::apiresource('countries', CountryController::class)
    ->only([ 'edit', 'store', 'update', 'destroy'])
    ->names([
        'edit' => 'countries.edit',
        'store' => 'countries.store',
        'update' => 'countries.update',
        'destroy' => 'countries.destroy',
]);
Route::apiresource('states', StateController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy', 'index'])
    ->names([
        'index' => 'states.show',
        'create' => 'states.create',
        'store' => 'states.store',
        'edit' => 'states.edit',
        'update' => 'states.update',
        'destroy' => 'states.destroy'
]);

Route::apiresource('cities', CityController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy', 'index' ])
    ->names([
        'index' => 'cities.show',
        'create' => 'cities.create',
        'store' => 'cities.store',
        'edit' => 'cities.edit',
        'update' => 'cities.update',
        'destroy' => 'cities.destroy'
]);
    Route::put('/cities/{id}', [CityController::class, 'update']);

    Route::get('/', function () {
        return view('app', [
            'componentName' => 'RegisterForm',
            'componentProps' => [],
        ]);
    });
    Route::post('/api/register', [LoginController::class, 'store']);

    
Route::get('/api/states/{country_id}/{user_id}', [LocationController::class, 'getStates']);
Route::get('/api/city/{states_id}/{country_id}/{user_id}', [LocationController::class, 'getCity']);

Route::prefix('api')->group(function () {
    // States Routes
    Route::get('/states', [StateController::class, 'index']);
    Route::post('/states', [StateController::class, 'store']);
    Route::get('/states/{state}', [StateController::class, 'show']);
    Route::put('/states/{state}', [StateController::class, 'update']);
    Route::delete('/states/{state}', [StateController::class, 'destroy']);

});
Route::get('/translations/{locale}', function ($locale) {
    App::setLocale($locale);
    return response()->json(trans('detail'));
});

