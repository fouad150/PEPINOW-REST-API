<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgotPassword']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password'); //retrieve only the email and the password from the request

        // $token = Auth::attempt($credentials); // auth::attempt() checks if the user exist and return true or false , and it create a session if true
        $token = JWTAuth::attempt($credentials); //check if the user exist if exist the it create a JW token
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user(); //retrieve the currently authenticated user instance
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', //unique:users check if the user is already exist in the database
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3,
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }


    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);


        // Find the user with the given email address
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Update the user's password
        // $user->password = Hash::make($request->password);
        $user->password = $request->password;
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }

    public function editProfile(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:5',
        ]);

        $id = Auth::id(); // Auth return the object of the authenticated user

        $user = User::find($id);

        // $user->password =  $request->password;
        $user->password =  Hash::make($request->password);
        $user->email = $request->email;
        $user->save();

        return response()->json(['message' => 'Profile updated successfully.']);
    }


    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
