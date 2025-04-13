<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdoptionRequestRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', AdoptionRequest::class);
    }

    public function rules()
    {
        return [
            'message' => 'required|string|max:1000',
        ];
    }
}
