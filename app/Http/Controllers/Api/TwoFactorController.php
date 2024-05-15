<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    //
    public function index(){
        return response()->json([
            'status' => true,
            'message' => 'User uuuu In Successfully',
            // 'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }
}
