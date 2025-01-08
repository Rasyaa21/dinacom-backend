<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use App\Http\Response\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255|regex:/^\S*$/',
            'password' => 'sometimes|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/',
            'profile_image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Nama harus berupa teks yang valid.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'name.regex' => 'Nama tidak boleh mengandung spasi.',

            'password.string' => 'Kata sandi harus berupa teks yang valid.',
            'password.min' => 'Kata sandi harus memiliki minimal 8 karakter.',
            'password.regex' => 'Kata sandi harus mengandung setidaknya satu huruf besar, satu huruf kecil, dan satu angka, serta satu karakter spesial.',

            'profile_image.image' => 'Gambar profil harus berupa file gambar.',
            'profile_image.mimes' => 'Gambar profil harus memiliki ekstensi: jpeg, png, jpg, gif, svg.',
            'profile_image.max' => 'Gambar profil tidak boleh lebih dari 2MB.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        $ApiResponse = new ApiResponse(422, $errors, 'validasi gagal');
        $res = $ApiResponse->toResponse($this->request);
        throw new HttpResponseException($res);
    }
}
