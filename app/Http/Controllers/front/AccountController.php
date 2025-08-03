<?php

namespace App\Http\Controllers\front;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function register(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:5',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // This will return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'status' => '400',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Now save user info in database
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            'status' => '200',
            'message' => 'User registered successfully',
        ], 200);
    }

    public function authenticate(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        // This will return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'status' => '400',
                'errors' => $validator->errors(),
            ], 400);
        }

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            // Authentication passed...
            // Generate a token for the user
            $user = User::find(Auth::user()->id);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => '200',
                'token' => $token,
                'name' => $user->name,
                'id' => Auth::user()->id,
                'message' => 'User authenticated successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Either email or password is incorrect',
            ], 401);
        }
    }
}
