<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Foundation\Http\FormRequest;

class BusinessCategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return BusinessCategoryRule::rules();
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {

        return BusinessCategoryRule::messages();
    }
}