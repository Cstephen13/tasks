<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    /**
     * Register a new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): \Illuminate\Http\Response
    {
        $fields = $request->validate([
           'name' => 'required|string',
           'email' => 'required|string|unique:users,email',
           'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);
        $token = $user->createToken(env('APP_KEY'))->plainTextToken;
        $response = [
          'access' => $token,
          'user' => $user->id,
        ];
        return response($response, 201);
    }

    /**
     * Register a new User.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        return response()->json([
            'token' => $request->user()->createToken("tasks")->plainTextToken,
            'message' => 'Success'
        ]);
    }
    /**
     * Register a new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        //
    }

    /**
     * Register a new User.
     *
     * @return UserResource|\Illuminate\Http\Response
     */
    public function user()
    {
        return new UserResource(auth()->user());
    }
}
