<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Validators\UserStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PassportController extends ApiController
{

    private $userService;

    /**
     * Create a new controller instance.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {

        //$this->middleware('api');
        $this->userService = $userService;
    }

    /**
     * Handles Login Request
     *
     * @return JsonResponse
     */
    public function login()
    {

        $storeRequest = new UserStoreRequest();
        $rules = $storeRequest->loginRules();

        $data = request()->only(['email', 'password']);

        $validator = Validator::make($data, $rules, $storeRequest->messages());

        if ($validator->fails()) {

            return $this->sendBadRequest('Validation Error.', $validator->errors()->toArray());
        }

        if (auth()->attempt($data)) {

            $user = auth()->user();
            $token = auth()->user()->createToken('AppName');
            $oauth_access_tokens = DB::table('oauth_access_tokens')->where('id', $token->token->id)->first();

            $data = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,

                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'full_name' => $user->full_name,

                'token' => $token->accessToken,
                'expires_at' => $oauth_access_tokens->expires_at,

            ];

            return response()->json($data, 200);

        } else {

            return $this->sendBadRequest('Username or password is invalid!');
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout()
    {
        request()->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function unAuthenticated()
    {

        return $this->sendUnauthorized();
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function me()
    {
        $user = auth()->user();
        //$roles = auth()->user()->roles;

        $data = [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
        ];

        return response()->json($data, 200);
    }

    public function profile()
    {
        $user = auth()->user();
        //$roles = auth()->user()->roles;
        $id = $user->id;
        $data = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'suffix' => $user->suffix,
            'title' => $user->title,
            'gender' => $user->gender,
            'birth' => $user->birth,
            'username' => $user->username,
            'password' => $user->password,
            'church_id' => $user->church_id,
            'custom_fields' => UserField::orderBy('order')->select('id', 'label', 'name', 'type', 'visual_group')->get(),
            'custom_data_fields' => UserField::orderBy('user_fields.order')
                ->leftJoin('user_data_fields', function ($join) use ($id) {
                    $join->on('user_data_fields.user_field_id', '=', 'user_fields.id')
                        ->where('user_data_fields.user_id', $id);
                })
                ->select(DB::raw('COALESCE(value,"") AS value'), 'name')
                ->get()
                ->pluck('value', 'name')
                ->toArray(),
            'roles' => auth()->user()->roles,
            'active' => $user->active,
            'country_id' => $user->country_id,
            'zipcode' => $user->zipcode,
            'state_id' => $user->state_id,
            'state_presenter' => $user->state_name,
            'city_id' => $user->city_id,
            'city_presenter' => $user->city_name,
            'address' => $user->address,
            'email' => $user->email,
            'full_name' => $user->full_name,
            //'token' => $token->accessToken,
            //'expires_at' => $oauth_access_tokens->expires_at,
            //'roles' => (new CustomFieldCollection($roles)),
        ];
        return response()->json($data, 200);
    }

    public function validateToken()
    {
        return response()->json([], 200);
    }

}
