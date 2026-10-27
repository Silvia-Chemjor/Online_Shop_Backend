<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ResendVerificationController extends Controller
{
    public function resend(Request $request){
        $request->validate([
            'email'=>'required|email'
        ]);
        
        $user = User::where('email',$request->email)->first();

        if(!$user){
            return response()->json([
                'message'=>'User not found.'
            ]);
        }

        if($user->hasVerifiedEmail()){
            return response()->json([
                'message'=>'Email is already verified.'
            ]);
        }

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
                'message'=>'A verification link has been resend to your email.'
            ]);
    }
}