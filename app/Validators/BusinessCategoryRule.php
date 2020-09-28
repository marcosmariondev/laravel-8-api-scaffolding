<?php

declare(strict_types=1);

namespace App\Validators;

class BusinessCategoryRule
{

    /**
     * Validation rules that apply to the request.
     *
     * @var array
     */
    protected static $rules = [
        //'id' => 'required|integer|exists:business_categories,id,deleted_at,NULL',
        'name' => 'required|max:255',
        'active' => 'required|boolean',
    ];

    /**
     * Return default rules
     *
     * @return array
     */
    public static function rules()
    {

        return [
            //'id' => self::$rules['id'],
            'name' => self::$rules['name'],
            //'active' => self::$rules['active'],
        ];
    }

    /**
     * Return default messages
     *
     * @return array
     */
    public static function messages()
    {

        return [];
    }
}
