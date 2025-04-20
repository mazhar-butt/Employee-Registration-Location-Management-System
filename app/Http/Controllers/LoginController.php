<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;

class LoginController extends Controller
{
    
    public function store(StoreUserRequest $request)
{
    $validatedData = $request->validated();

    $user = User::create([
        'first_name' => $validatedData['first_name'],
        'last_name' => $validatedData['last_name'],
        'email' => $validatedData['email'],
        'username' => $validatedData['username'],
        'password' => bcrypt($validatedData['password']),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Registration successful!',
        'user_id' => $user->id, 
    ]);
}

}
