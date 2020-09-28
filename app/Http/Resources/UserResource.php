<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Modules\Authentication\Models\User;
use Modules\Authentication\Models\UserField;
use Modules\Authentication\Services\UserService;

class UserResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {

        $user = new User();
        $user->country_id = $this->country_id;
        $user->state_id = $this->state_id;
        $user->city_id = $this->city_id;
        $user->zipcode = $this->zipcode;
        $user->address = $this->address;
        $user->complement = $this->complement;
        $user->number = $this->number;
        $user->neighborhood = $this->neighborhood;

        $performAddressing = (new UserService())->performAddressing($user);

        $id = $this->id;

        return [

            'id' => $id,
            'family_id' => $this->family_id,

            'country_id' => $this->country_id,
            'country_presenter' => $performAddressing['country_presenter'],

            'city_id' => $this->city_id,
            'city_presenter' => $performAddressing['city_presenter'],
            'city_name' => $this->city_name,

            'state_id' => $this->state_id,
            'state_presenter' => $performAddressing['state_presenter'],
            'state_name' => $this->state_name,

            'church_id' => $this->church_id,
            'church_presenter' => $this->church->name,

            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,

            'suffix' => $this->suffix,
            'title' => $this->title,

            'gender' => $this->gender,
            'gender_presenter' => config('enums.genders.' . $this->gender),

            'birth' => $this->birth ? $this->birth->format('Y-m-d') : '',
            'birth_presenter' => $this->birth ? $this->birth->format('M d Y') : '',

            'username' => $this->username,
            'roles' => $this->roles->pluck('id')->toArray(),
            'business_categories' => $this->businessCategories->pluck('id')->toArray(),

            'phone_home' => $this->phone_home,
            'phone_work' => $this->phone_work,
            'phone_mobile' => $this->phone_mobile,

            'email' => $this->email,
            'email_other' => $this->email_other,
            'email_alternative' => $this->email_alternative,

            'social_facebook' => $this->social_facebook,
            'social_twitter' => $this->social_twitter,
            'social_linked_in' => $this->social_linked_in,
            'social_instagram' => $this->social_instagram,
            'social_snapchat' => $this->social_snapchat,
            'social_whats_app' => $this->social_whats_app,

            'zipcode' => $this->zipcode,
            'address' => $this->address,
            'complement' => $this->complement,
            'number' => $this->number,
            'neighborhood' => $this->neighborhood,
            'address_full' => $performAddressing['address_complete'],
            'address_google_maps' => $performAddressing['address_google_maps'],

            'active' => $this->active,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'distance' => $this->distance,
            'distance_presenter' => $this->distance ? (($this->distance * 0.00062137) . ' miles') : null,
            'custom_fields' => UserField::orderBy('order')->select('id', 'label', 'name', 'type', 'visual_group', 'order')->get(),
            'custom_data_fields' => UserField::orderBy('user_fields.order')
                ->leftJoin('user_data_fields', function ($join) use ($id) {
                    $join->on('user_data_fields.user_field_id', '=', 'user_fields.id')
                        ->where('user_data_fields.user_id', $id);
                })
                ->select(DB::raw('COALESCE(value,"") AS value'), 'name')
                ->get()
                ->pluck('value', 'name')
                ->toArray(),
        ];
    }
}
