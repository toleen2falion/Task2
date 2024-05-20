<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Events\emailSendEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\verifyEemailRequest;
use App\Http\Requests\loginUserRequest;
use App\Http\Requests\DeleteUserAccountRequest;
use App\Traits\GeneralTrait;

class AuthUserController extends Controller
{
    use GeneralTrait;
    
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createUser(CreateUserRequest $request)
    {
       
        
            $user = new User;

            if($request->hasFile('profile_photo')) 
                $user->profile_photo =  $this->fileUpload('public/profile_photo', $request->profile_photo);
             
            
        
            if($request->hasFile('certificate')) 
                $user->certificate =  $this->fileUpload('public/certificate',$request->certificate);
            
            $user->name=$request->name;
            $user->email=$request->email;
            $user->phone=$request->phone;
            $user->password= Hash::make($request->password);

            $user->save();
            $user->generateCode();
        event(new emailSendEvent($user));

       return $this->successMessage('The code has been sent, please check your email',200);
   
    }

        ///
    public function verifyEemail(verifyEemailRequest $request)
    {
           
            $user = User::where('email', $request->email)->first();
            if($user)
            {
                if ($request->code==$user->code && now()<=$user->expire_at)
                    {
                        $user->restCode();
                        return $this->createNewToken('Your email has been verified, and you have successfully logged in.',200,$user);

                    }

                else
                    {
                        $user->generateCode();
                        event(new emailSendEvent($user));
                        return $this->errorMessage("The code is incorrect. We have re-sent it to your email, Please check.",401);
                       
                    }

            }

            else{
                return $this->errorMessage("email is incorrect.",401);
            }

    }

        //


     /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(loginUserRequest $request)
    {

            if(!Auth::attempt($request->only(['email', 'password','phone'])))
             return $this->errorMessage("Email & Password & phone number does not match with our record.",401);
        
            
            $user = User::where('email', $request->email)->first();

            //
            $user->generateCode();
            event(new emailSendEvent($user));
           return $this->successMessage('The code has been sent, please check your email',200);
          
            //

        
    }

    public function logout(){
        
        auth()->user()->tokens()->delete();
        return $this->successMessage('User Logged out',200);
    

    }

    public function refreshToken()
    {
        // $token = Auth::refresh();
        $user =Auth::user();
        auth()->user()->tokens()->delete();
        return $this->createNewToken('Refresh user token.',200,$user);

    }

   
 public function DeleteUserAccount(DeleteUserAccountRequest $request)
 {
  
         $user = User::where('email', $request->email)->first();

        if(!$user)
         return $this->errorMessage("user not found",404);

            if($user->profile_photo)
            $delete_profile_photo =  $this->fileDelete('profile_photo',$user->profile_photo);
           

            if($user->certificate)
            $delete_certificate =  $this->fileDelete('certificate',$user->certificate);

            $user->delete();

            return $this->successMessage('The user and his files have been successfully deleted',200);
        
           

 }

    
}


