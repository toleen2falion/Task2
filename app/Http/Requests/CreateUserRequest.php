<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'profile_photo'=>'image|unique:users,profile_photo',
            'certificate'=>'mimes:pdf|unique:users,certificate',
            'name' => 'required',
            'phone'=>'required|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            // 'password' => 'min:6',
            'password_confirmation' => 'required_with:password|same:password'
        ];
    }
    
}
