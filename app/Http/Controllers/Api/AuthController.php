<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8|string',
        ], [
            'name.required' => 'Field name required.',
            'name.string' => 'Field name must a string.',
            'name.max' => 'Field name max 255 characters.',
            
            'email.required' => 'Field email required.',
            'email.string' => 'Field email must a string.',
            'email.email' => 'Email must be a email format.',
            
            'password.required' => 'Field password required.',
            'password.string' => 'Field password must a string.',
            'password.min' => 'Field password min 8 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $users = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Register Successfully.',
            'data' => [
                'User' => [
                    'name' => $users->name,
                    'email' => $users->email,
                ]
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|string',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if(!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.'
            ], 401);
        }
        
        $users = Auth::user();
        $token = $request->user()->createToken('token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Successfully.',
            'data' => [
                'User' => [
                    'Name' => $users->name,
                    'Email' => $users->email,
                ], 
                'Token' => [
                    'access_token' => $token,
                    'Type' => 'bearer',
                ]
            ]
        ], 200);
    }
    
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout Successfully.'
        ], 200);
    }
}
