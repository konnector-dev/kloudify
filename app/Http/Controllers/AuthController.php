<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Http\Requests\StoreUserRequest;
use App\Repositories\UserRepository;

class AuthController extends Controller
{
    public function register(Request $request) 
    {
        
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
 
        $token = $user->createToken('TutsForWeb')->accessToken;
 
        return response()->json(['token' => $token], 200);
        // print_r($request);exit;
        // $user = $userRepository->create($request);
        // $token = $user->createToken('Laravel')->accessToken;
        
        // return response()->json(['user' => $user], 201);
    }


}
