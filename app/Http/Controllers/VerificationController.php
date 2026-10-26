<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(Request $request, $id, $hash){
        $user = User::where('id', $id)->first();

        if(!hash_equals((string)$hash, sha1($user->email))){
            return response()->json([
                'message' => 'Invalid verification link'
            ]);
        }

        if($user->hasVerifiedEmail()){
            return response()->json([
                'message' => 'Email is already verified'
            ]);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        $user->is_active = 1;
        $user->save();

        return response()->json("Email Successfully Verified");
    }
}
