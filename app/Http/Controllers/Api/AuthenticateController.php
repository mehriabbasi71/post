<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticateController extends Controller
{
    public function index(Request $request) {
        if(!$request->username) {
            return response()->json(['message' => 'Sending username is necessary'],404);
        }
        $user = User::where(['email' => $request->username])->orWhere(['mobile' => $request->username])->exists();
        if(!$user) {
            if(!$request->name or empty($request->name)) {
                return response()->json(['message' => 'Sending name is necessary'],404);
            }

            $user = new User();
            if(is_numeric($request->username)) {
                $user->mobile = $request->username;
            } else {
                $user->email = $request->username;
            }
            $user->name = $request->name;
            $user->password = Hash::make($request->username);
            $user->save();
        }
        return response()->json(['message' => 'Code will be send'],200);
    }

    public function verify(Request $request) {
        if(!$request->username) {
            return response()->json(['message' => 'Sending username is necessary'],404);
        }

        if(!$request->code) {
            return response()->json(['message' => 'Sending code is necessary'],404);
        }
        $user = User::where(['email' => $request->username])->orWhere(['mobile' => $request->username])->first();
        if(!$user) {
            return response()->json(['message' => 'User is not available'],404);
        }
        $token = $user->createToken('token-name');
        return response()->json(['token' => $token->plainTextToken],200);
    }
}
