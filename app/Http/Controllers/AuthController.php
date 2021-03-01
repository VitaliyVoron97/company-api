<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:2|max:255',
            'phone' => 'required|min:5|max:255',
        ]);

        if ($validator->fails()) {
            return response(['message' => 'register validation error', 'errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return response(['message' => "User {$data['first_name']} {$data['last_name']} is created successfully"], 201);
    }

    public function signIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:2|max:255',
        ]);

        if ($validator->fails()) {
            return response(['message' => 'sign-in validation error', 'errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        $user = User::where('email', $data['email'])->first();

        if ($user && !Hash::check($data['password'], $user->password)) {
            return response(['message' => 'incorrect email or password'], 422);
        }

        $apiToken = base64_encode(Str::random(40));
        $user->update(['api_token' => $apiToken]);

        return response(['api_token' => $apiToken]);
    }
}
