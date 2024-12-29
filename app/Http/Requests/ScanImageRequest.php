<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Response\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class ScanImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'trash_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'trash_image.required' => 'Gambar sampah wajib diunggah.',
            'trash_image.image' => 'File yang diunggah harus berupa gambar.',
            'trash_image.mimes' => 'Gambar harus memiliki format jpg, jpeg, atau png.',
            'trash_image.max' => 'Ukuran gambar tidak boleh lebih dari 2 MB.',
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
