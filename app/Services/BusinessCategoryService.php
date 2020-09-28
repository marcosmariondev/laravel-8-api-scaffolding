<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Models\BusinessCategory;

class BusinessCategoryService
{

    public function paginate(int $limit): LengthAwarePaginator
    {

        return $this->buildQuery()->paginate($limit);
    }

    private function buildQuery(): Builder
    {

        $query = BusinessCategory::query();

        $query->when(request('id'), function ($query, $id) {

            return $query->whereId($id);
        });

        $query->when(request('name'), function ($query, $search) {

            return $query->where('name', 'LIKE', '%' . $search . '%');
        });

        $query->when(request('sortKey'), function ($query, $sortKey) {

            if (request('reverse') == 'true') {

                return $query->orderByDesc($sortKey);
            } else {

                return $query->orderBy($sortKey);
            }
        });

        if (empty(request('sortKey'))) {

            return $query->orderBy('name');
        }

        return $query;
    }

    public function all(): Collection
    {

        return $this->buildQuery()->get();
    }

    public function find(int $id): ?BusinessCategory
    {

        return BusinessCategory::find($id);
    }

    public function create(array $data): BusinessCategory
    {

        $businessCategory = new BusinessCategory();
        $businessCategory->fill($data);
        $businessCategory->save();

        return $businessCategory;
        //return DB::transaction(function () use ($data) {
        //});
    }

    public function update(array $data, BusinessCategory $businessCategory): BusinessCategory
    {

        $businessCategory->fill($data);
        $businessCategory->save();

        return $businessCategory;
    }

    public function delete(BusinessCategory $businessCategory): ?bool
    {

        return $businessCategory->delete();
    }

    public function listOfChoices(): array
    {

        return BusinessCategory::select('id', 'name as label')
            ->orderBy('name')
            ->get()
            ->toArray();

        // $query = BusinessCategory::find(1)->toArray();
        //return $query;

    }
}
