<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Events\emailSendEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthUserController extends Controller
{

    // public function __construct(){
    //     $this->middleware('auth:sanctum')->except('createUser');
        
        
    // }
    //
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'profile_photo'=>'image',
                'certificate'=>'mimes:pdf',
                'name' => 'required',
                'phone'=>'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                // 'password' => 'min:6',
                'password_confirmation' => 'required_with:password|same:password'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $user = new User;

            if($request->hasFile('profile_photo')) {
                $createnewFileName = $request->profile_photo->hashName();
                $img_path = $request->file('profile_photo')->storeAs('public/profile_photo', $createnewFileName); // get the image path
                $user->profile_photo = $createnewFileName; // pass file name with column
            }
            if($request->hasFile('certificate')) {
                $createnewFileName = $request->certificate->hashName();
                $img_path = $request->file('certificate')->storeAs('public/certificate', $createnewFileName); // get the image path
                $user->certificate = $createnewFileName; // pass file name with column
            }
            $user->name=$request->name;
            $user->email=$request->email;
            $user->phone=$request->phone;
            $user->password= Hash::make($request->password);
          
            $user->save();
            $user->generateCode();
        event(new emailSendEvent($user));

            
            return response()->json([
                    'status' => true,
                    'message' => 'User Created Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);      
           
          
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

     /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'phone'=>'required',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password','phone']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password & phone number does not match with our record.',
                ], 401);
            }

            
            $user = User::where('email', $request->email)->first();

            //
            // $user->generateCode();
            //

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User Logged out',
       
        ], 200);

    }

    public function refreshToken()
    {
        // $token = Auth::refresh();
        $user =Auth::user();
        return response()->json([
            'status' => true,
            'token' => $user->createToken("API TOKEN")->plainTextToken
       
        ], 200);

    }

   


    
}


