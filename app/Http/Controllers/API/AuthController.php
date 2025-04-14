<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validation =  Validator::make(request()->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($validation->fails()) {
            $json['error'] = $validation->errors();
            $json['test'] = 'oke';
            return response()->json($json, 200);
        }
        //'password' => Hash::make($request->password),

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation
        ]);

        try {
            $token = JWTAuth::fromUser($user);
        } catch (\Throwable $e) {
            // } catch (JWTException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logout Success']);
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }
        $credential = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credential)) {
            return response()->json(['error' => 'Unathorized'], 422);
        }
        return response()->json(['token' => $token], 200);
    }

    public function me()
    {
        try {
            //code...
            $user = auth()->guard('api')->user();
            return response()->json([
                'message' => 'Fetch Profile user success',
                'user' => $user,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    // public function getUser()
    // {
    //     try {
    //         $user = Auth::user();
    //         if (!$user) {
    //             return response()->json(['error' => 'User not found'], 404);
    //         }
    //         return response()->json($user);
    //     } catch (JWTException $e) {
    //         return response()->json(['error' => 'Failed to fetch user profile'], 500);
    //     }
    // }

    // public function updateUser(Request $request)
    // {
    //     try {
    //         $user = Auth::user();
    //         $user->update($request->only(['name', 'email']));
    //         return response()->json($user);
    //     } catch (JWTException $e) {
    //         return response()->json(['error' => 'Failed to update user'], 500);
    //     }
    // }
}
