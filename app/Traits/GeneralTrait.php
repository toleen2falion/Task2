<?php
namespace App\Traits;

trait GeneralTrait{

public function errorMessage($message,$code){
    return response()->json([
        'status' => false,
        'message' => $message,
   
    ],$code);
}

public function successMessage($message,$code){
    return response()->json([
        'status' => true,
        'message' => $message,
   
    ],$code);
}



public function createNewToken($message,$code,$user){
    return response()->json([
        'status' => true,
        'message' => $message,
        'token' => $user->createToken("API TOKEN")->plainTextToken
    ],$code);
}

//files
public function fileUpload($folderStoragePath,$image){
    $extension=strtolower($image->extension());
    $filename=time().rand(1,10000).".".$extension;
    $image->storeAs($folderStoragePath, $filename);
    return  $filename;
      }

      ///

public function fileDelete($folderStoragePath,$file){
    unlink(storage_path('app/public/'.$folderStoragePath.'/'.$file));
    return $folderStoragePath." "."deleted";
    }

/////
public function errorValidationMessage($error){

    
 
    // $errorMessage = '<ul>'; 
    // foreach ($error as $errorMessageItem) { 
    //     $errorMessage .= '<li>' . $errorMessageItem . '</li>'; 
    // } 
    // $errorMessage .= '</ul>'; 

    // return response()->json([ 
    //     'status' => false, 
    //     'message' => 'Validation error', 
    //     'error' => $errorMessage 
    //   ], 422); 



    return response()->json([
        'status' => false,
        'message' => 'validation error',
        'errors' => $error
        // 'errors' => print_r($$e->errors())
     ], 422);
             
    }

}