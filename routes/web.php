<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CityController;

Route::get('/register', function () {
    return view('app');
});
Route::post('/register', [LoginController::class, 'store']);

Route::get('/country/{user_id}', function ($user_id) {
    $countries = \App\Models\Country::all();
    return view('app', compact('user_id', 'countries'));
});

Route::post('/store-country', [EmployeeController::class, 'store'])->name('country.store');

Route::get('/state/{country_id}/{user_id}', function ($country_id, $user_id) {
    $states = \App\Models\State::where('country_id', $country_id)->get();
    return view('app', compact('country_id', 'user_id', 'states'));
})->name('state.form');

Route::post('/store-state', [EmployeeController::class, 'storeState'])->name('state.store');

Route::get('/city/{state_id}/{country_id}/{user_id}', function ($state_id, $country_id, $user_id) {
    $cities = \App\Models\City::where('state_id', $state_id)->get();
    return view('app', compact('state_id', 'country_id', 'user_id', 'cities'));
})->name('city.form');

Route::post('/store-city', [EmployeeController::class, 'storeCity'])->name('city.store');



Route::get('/detail/{city_id}/{state_id}/{country_id}/{user_id}', function ($city_id, $state_id, $country_id, $user_id) {
    return view('app', compact('city_id', 'state_id', 'country_id', 'user_id'));
})->name('detail');
Route::post('/store-detail', [EmployeeController::class, 'detail'])->name('detail.store');

// Success Route
Route::get('/success', function () {
    return view('success');
})->name('success');

// Login Routes
Route::get('/login', function () {
    return view('login');
})->name('login');


// Admin Dashboard Routes
Route::get('/detail', [AdminController::class, 'showEmployeeDetails'])
    ->name('admin.dashboard');
    Route::get('/cities/{stateId}', [AdminController::class, 'getCities']);

// Admin Details Routes
Route::resource('details', AdminController::class)
    ->only(['index', 'edit', 'update', 'destroy', ])
    ->names([
        'index' => 'details.index',
        'edit' => 'detail.edit',
        'update' => 'detail.update',
        'destroy' => 'detail.destroy'
]);
Route::post('/location', [LocationController::class, 'store']);
Route::get('/location', [LocationController::class, 'index'])->name('location.index');

Route::resource('countries', CountryController::class)
    ->only(['create', 'edit', 'update', 'destroy', 'index'])
    ->names([
        'index' => 'countries.show',
        'create' => 'countries.create',
        'edit' => 'countries.edit',
        'update' => 'countries.update',
        'destroy' => 'countries.destroy'
]);


Route::resource('states', StateController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy', 'index'])
    ->names([
        'index' => 'states.show',
        'create' => 'states.create',
        'store' => 'states.store',
        'edit' => 'states.edit',
        'update' => 'states.update',
        'destroy' => 'states.destroy'
]);


Route::resource('cities', CityController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy', 'index' ])
    ->names([
        'index' => 'cities.show',
        'create' => 'cities.create',
        'store' => 'cities.store',
        'edit' => 'cities.edit',
        'update' => 'cities.update',
        'destroy' => 'cities.destroy'
]);

