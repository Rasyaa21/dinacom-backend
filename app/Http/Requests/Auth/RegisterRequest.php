<?php

namespace App\Http\Requests\Auth;

use App\Http\Response\ApiResponse;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:users,name|regex:/^\S*$/',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Kolom nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks yang valid.',
            'name.regex' => 'Nama tidak boleh mengandung spasi.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'email.required' => 'Kolom email wajib diisi.',
            'email.string' => 'Email harus berupa teks yang valid.',
            'email.unique' => 'Email sudah digunakan.',

            'password.required' => 'Kolom kata sandi wajib diisi.',
            'password.string' => 'Kata sandi harus berupa teks yang valid.',
            'password.min' => 'Kata sandi harus memiliki minimal 8 karakter.',
            'password.regex' => 'Kata sandi harus mengandung setidaknya satu huruf besar, satu huruf kecil, dan satu angka.',
        ];
    }

    protected function failedValidation(ValidationValidator $validator)
    {
        $errors = $validator->errors()->toArray();
        $ApiResponse = new ApiResponse(422, $errors, 'validasi gagal');
        $res = $ApiResponse->toResponse($this->request);
        throw new HttpResponseException($res);
    }
}
