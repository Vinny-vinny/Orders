<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
           'email' => 'required|string|email|max:255',
           'password' => 'required|string',
        ],[
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        if (Auth::attempt(['email'=>$request->email,'password'=>$request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('Orders')->plainTextToken;
            $success['name'] = $user->fullname;
            $success['userId'] = $user->id;
            return $this->sendResponse($success, 'User logged in successfully.');
        }else {
            return $this->sendError('Invalid Login Credentials.', ['error'=>'Unauthorised'], 401);
        }
    }

    public function logout(Request $request)
    {
        // Revoke the user's current token
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });
        // Return a response
        return $this->sendResponse([], 'User logged out successfully.');
    }
}
