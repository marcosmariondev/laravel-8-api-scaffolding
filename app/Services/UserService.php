<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserService
{

    public $concatNameExpression = 'CONCAT(
                                COALESCE(first_name,""), " ",
                                COALESCE(middle_name,""), " ",
                                COALESCE(last_name,"")
                            )';

    public function paginate(int $limit): LengthAwarePaginator
    {

        return $this->buildQuery()->paginate($limit);
    }

    private function buildQuery(): Builder
    {

        $expression = $this->concatNameExpression;

        $query = User::query();

        $query->when(request('id'), function ($query, $id) {

            return $query->whereId($id);
        });

        $query->when(request('search'), function ($query, $search) use ($expression) {

            return $query->where(function ($q) use ($search, $expression) {
                $q->whereRaw($expression . ' LIKE ?',
                    [
                        "%{$search}%",
                    ]
                );
            });
        });

        $query->when(request('email'), function ($query, $search) {

            return $query->where('email', 'LIKE', '%' . $search . '%');
        });

        $query->when(request('first_name'), function ($query, $search) {

            return $query->where('first_name', 'LIKE', '%' . $search . '%');
        });

        $query->when(request('last_name'), function ($query, $search) {

            return $query->where('last_name', 'LIKE', '%' . $search . '%');
        });

        $query->when(request('username'), function ($query, $search) {

            return $query->where('username', 'LIKE', '%' . $search . '%');
        });

        $query->when(request('gender'), function ($query, $gender) {

            return $query->where('gender', $gender);
        });

        $query->when(request('sortKey'), function ($query, $sortKey) {

            if (request('reverse') == 'true') {

                return $query->orderByDesc($sortKey);
            } else {

                return $query->orderBy($sortKey);
            }
        });

        if (empty(request('sortKey'))) {

            return $query->orderBy('first_name');
        }

        return $query->with('city.state.country');
    }

    public function all(): Collection
    {

        return $this->buildQuery()->get();
    }

    public function find(int $id): ?User
    {

        return User::find($id);
    }

    public function findUser(int $id): array
    {

        return User::find($id)->toArray();
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $user = new User();
            $data['birth'] = substr($data['birth'], 0, 10);
            $user->fill($data);

            if (array_key_exists('password', $data) && !empty($data['password'])) {

                $user->password = bcrypt($data['password']);
            }

            $maps = $this->getCoordinates($user);
            $user->lat = $maps['lat'];
            $user->lng = $maps['lng'];
            $user->distance = $maps['distance'];

            $user->save();

            if (!array_key_exists('roles', $data)) {

                $data['roles'] = [];
            }
            $user->roles()->sync($data['roles']);

            if (!array_key_exists('business_categories', $data)) {

                $data['business_categories'] = [];
            }
            $data['business_categories'] = array_filter($data['business_categories']);

            $user->businessCategories()->sync($data['business_categories']);

            if (!array_key_exists('custom_data_fields', $data)) {

                $data['custom_data_fields'] = [];
            }

            $this->saveCustomFields($user, $data['custom_data_fields']);

            return $user;
        });
    }

    public function update(array $data, User $user): User
    {

        return DB::transaction(function () use ($user, $data) {

            $data['birth'] = substr($data['birth'], 0, 10);
            $user->fill($data);

            if (array_key_exists('password', $data) && !empty($data['password'])) {

                $user->password = bcrypt($data['password']);
            }

            if (
                ($user->getOriginal('country_id') != $user->country_id) ||
                ($user->getOriginal('state_id') != $user->state_id) ||
                ($user->getOriginal('city_id') != $user->city_id) ||
                ($user->getOriginal('state_name') != $user->state_name) ||
                ($user->getOriginal('city_name') != $user->city_name) ||
                ($user->getOriginal('address') != $user->address)
            ) {

                $maps = $this->getCoordinates($user);
                $user->lat = $maps['lat'];
                $user->lng = $maps['lng'];
                $user->distance = $maps['distance'];
            }

            $user->save();

            if (!array_key_exists('roles', $data)) {

                $data['roles'] = [];
            }
            $user->roles()->sync($data['roles']);

            if (!array_key_exists('business_categories', $data)) {

                $data['business_categories'] = [];
            }
            $data['business_categories'] = array_filter($data['business_categories']);
            $user->businessCategories()->sync($data['business_categories']);

            if (!array_key_exists('custom_data_fields', $data)) {

                $data['custom_data_fields'] = [];
            }

            $this->saveCustomFields($user, $data['custom_data_fields']);

            return $user;
        });
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }

}
