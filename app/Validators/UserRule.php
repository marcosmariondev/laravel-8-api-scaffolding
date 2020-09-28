<?php

declare(strict_types=1);

namespace  App\Validators;

use Illuminate\Support\Facades\Auth;

class UserRule
{

    /**
     * Validation rules that apply to the request.
     *
     * @var array
     */
    protected static $rules = [
        //'id' => 'required|integer|exists:users,id,deleted_at,NULL',
        'roles' => 'required|array',
        'role_id' => 'required|integer|exists:roles,id,deleted_at,NULL',
        'church_id' => 'nullable|integer|exists:churches,id,deleted_at,NULL',
        'country_id' => 'required|integer|exists:countries,id,deleted_at,NULL',
        'state_id' => 'nullable|integer|exists:states,id,deleted_at,NULL',
        'city_id' => 'nullable|integer|exists:cities,id,deleted_at,NULL',
        'first_name' => 'required|max:255',
        'middle_name' => 'nullable|max:255',
        'last_name' => 'required|max:255',
        'suffix' => 'nullable|max:20',
        'title' => 'nullable|max:20',
        'gender' => 'required|in:MALE,FEMALE',
        'birth' => 'required|date',
        'username' => 'required|unique:users,username',
        'password' => 'required|string|min:6|confirmed',
        'password_update' => 'nullable|string|min:6|confirmed',
        'phone_home' => 'nullable|max:20',
        'phone_work' => 'nullable|max:20',
        'phone_mobile' => 'nullable|max:20',
        'email' => 'nullable|email|max:255',
        'email_other' => 'nullable|email|max:255',
        'email_alternative' => 'nullable|email|max:255',
        'social_facebook' => 'nullable|max:255',
        'social_twitter' => 'nullable|max:255',
        'social_linked_in' => 'nullable|max:255',
        'social_instagram' => 'nullable|max:255',
        'social_snapchat' => 'nullable|max:255',
        'social_whats_app' => 'nullable|max:20',
        'zipcode' => 'nullable|max:20',
        'address' => 'nullable|max:255',
        'complement' => 'nullable|max:255',
        'number' => 'nullable|max:255',
        'neighborhood' => 'nullable|max:255',
        'city_name' => 'nullable|max:255',
        'state_name' => 'nullable|max:255',
        'active' => 'required|boolean',
    ];

    /**
     * Return default rules
     *
     * @return array
     */
    public static function rulesCreateMember()
    {

        return [
            'church_id' => self::$rules['church_id'],
            'country_id' => self::$rules['country_id'],
            'state_id' => self::$rules['state_id'],
            'city_id' => self::$rules['city_id'],
            'first_name' => self::$rules['first_name'],
            'middle_name' => self::$rules['middle_name'],
            'last_name' => self::$rules['last_name'],
            'suffix' => self::$rules['suffix'],
            'title' => self::$rules['title'],
            'gender' => self::$rules['gender'],
            'birth' => self::$rules['birth'],
            'username' => self::$rules['username'],
            'password' => self::$rules['password'],
            'roles' => self::$rules['roles'],
            'roles.*' => self::$rules['role_id'],
            'phone_home' => self::$rules['phone_home'],
            'phone_work' => self::$rules['phone_work'],
            'phone_mobile' => self::$rules['phone_mobile'],
            'email' => self::$rules['email'],
            'email_other' => self::$rules['email_other'],
            'email_alternative' => self::$rules['email_alternative'],
            'social_facebook' => self::$rules['social_facebook'],
            'social_twitter' => self::$rules['social_twitter'],
            'social_linked_in' => self::$rules['social_linked_in'],
            'social_instagram' => self::$rules['social_instagram'],
            'social_snapchat' => self::$rules['social_snapchat'],
            'social_whats_app' => self::$rules['social_whats_app'],
            'zipcode' => self::$rules['zipcode'],
            'address' => self::$rules['address'],
            'complement' => self::$rules['complement'],
            'number' => self::$rules['number'],
            'neighborhood' => self::$rules['neighborhood'],
            'city_name' => self::$rules['city_name'],
            'state_name' => self::$rules['state_name'],
            'active' => self::$rules['active'],
        ];
    }

    public static function rulesUpdateMember()
    {

        return [
            'church_id' => self::$rules['church_id'],
            'country_id' => self::$rules['country_id'],
            'state_id' => self::$rules['state_id'],
            'city_id' => self::$rules['city_id'],
            'first_name' => self::$rules['first_name'],
            'middle_name' => self::$rules['middle_name'],
            'last_name' => self::$rules['last_name'],
            'suffix' => self::$rules['suffix'],
            'title' => self::$rules['title'],
            'gender' => self::$rules['gender'],
            'birth' => self::$rules['birth'],
            'username' => self::$rules['username'] . ',' . request('user')->id,
            'password' => self::$rules['password_update'],
            'roles' => self::$rules['roles'],
            'roles.*' => self::$rules['role_id'],
            'phone_home' => self::$rules['phone_home'],
            'phone_work' => self::$rules['phone_work'],
            'phone_mobile' => self::$rules['phone_mobile'],
            'email' => self::$rules['email'],
            'email_other' => self::$rules['email_other'],
            'email_alternative' => self::$rules['email_alternative'],
            'social_facebook' => self::$rules['social_facebook'],
            'social_twitter' => self::$rules['social_twitter'],
            'social_linked_in' => self::$rules['social_linked_in'],
            'social_instagram' => self::$rules['social_instagram'],
            'social_snapchat' => self::$rules['social_snapchat'],
            'social_whats_app' => self::$rules['social_whats_app'],
            'zipcode' => self::$rules['zipcode'],
            'address' => self::$rules['address'],
            'complement' => self::$rules['complement'],
            'number' => self::$rules['number'],
            'neighborhood' => self::$rules['neighborhood'],
            'city_name' => self::$rules['city_name'],
            'state_name' => self::$rules['state_name'],
            'active' => self::$rules['active'],
        ];
    }

    public static function rulesUpdateProfile()
    {

        return [
            'church_id' => self::$rules['church_id'],
            'country_id' => self::$rules['country_id'],
            'state_id' => self::$rules['state_id'],
            'city_id' => self::$rules['city_id'],
            'first_name' => self::$rules['first_name'],
            'middle_name' => self::$rules['middle_name'],
            'last_name' => self::$rules['last_name'],
            'suffix' => self::$rules['suffix'],
            'title' => self::$rules['title'],
            'gender' => self::$rules['gender'],
            'birth' => self::$rules['birth'],
            'username' => self::$rules['username'] . ',' . Auth::id(),
            'password' => self::$rules['password_update'],
            'phone_home' => self::$rules['phone_home'],
            'phone_work' => self::$rules['phone_work'],
            'phone_mobile' => self::$rules['phone_mobile'],
            'email' => self::$rules['email'],
            'email_other' => self::$rules['email_other'],
            'email_alternative' => self::$rules['email_alternative'],
            'social_facebook' => self::$rules['social_facebook'],
            'social_twitter' => self::$rules['social_twitter'],
            'social_linked_in' => self::$rules['social_linked_in'],
            'social_instagram' => self::$rules['social_instagram'],
            'social_snapchat' => self::$rules['social_snapchat'],
            'social_whats_app' => self::$rules['social_whats_app'],
            'zipcode' => self::$rules['zipcode'],
            'address' => self::$rules['address'],
            'complement' => self::$rules['complement'],
            'number' => self::$rules['number'],
            'neighborhood' => self::$rules['neighborhood'],
            'city_name' => self::$rules['city_name'],
            'state_name' => self::$rules['state_name'],
            'active' => self::$rules['active'],
        ];
    }

    public static function loginRules()
    {

        return [
            'email' => 'required',
            'password' => 'required|string|min:6',
        ];
    }

    public static function sendPasswordResetLinkRules()
    {

        return [
            'username' => 'required|exists:users,username,deleted_at,NULL',
        ];
    }

    public static function newFieldsRule()
    {

        return [
            'type' => 'required',
            'label' => 'required|unique:user_fields,label',
            'visual_group' => 'required|numeric',
            'options' => 'required_if:type,SELECT|array|min:2',
        ];
    }

    public static function updateAllFieldsRule()
    {

        $rules = [];
        $rules['fields'] = 'required|array';

        foreach (request('fields') as $index => $field) {

            $rules['fields.' . $index . '.label'] = 'required|unique:user_fields,label,' . $field['id'];
        }
        return $rules;
    }

    /**
     * Return default messages
     *
     * @return array
     */
    public static function messages()
    {

        return [
            'fields.*.label.unique' => 'Has already been taken',
        ];
    }
}
