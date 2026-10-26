<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:3|confirmed'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        try{
            $user->save();

            $signedUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                [
                    'id'=>$user->id,
                    'hash'=>sha1($user->email)
                ]
            );

            $user->notify(new VerifyEmailNotification($signedUrl));
            
            return response()->json([
                'message'=>'A verification link has been sent to your email address'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Failed to register user',
                'error'=>$e->getMessage()
            ], 500);
        }
    }
    
    public function login(Request $request) {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:3',
        ]);

        try{
        $user = User::where('email', $request->email)->first();
        
        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                'message'=>'Invalid credentials',
            ], 401);
        }

        if(!$user->is_active){
            return response()->json([
                'message' => 'Your account is not activated, please verify yourr email'
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message'=>'Login successful!',
            'user'=>$user,
            'token'=>$token
        ], 201);}catch(\Exception $e){
            return response()->json([
                'message'=>'Failed to login user',
                'error'=>$e->getMessage()
            ], 500);
        }

    }
    public function logout(Request $request){
        $user = $request->user()->currentAccessToken()->delete();

        return response()->json('Logout success');
   
        }
}
