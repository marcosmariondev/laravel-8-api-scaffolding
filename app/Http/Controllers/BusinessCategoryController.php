<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BusinessCategoryCollection;
use App\Http\Resources\BusinessCategoryResource;
use App\Models\BusinessCategory;
use App\Services\BusinessCategoryService;
use App\Validators\BusinessCategoryStoreRequest;
use App\Validators\BusinessCategoryUpdateRequest;

class BusinessCategoryController extends ApiController
{

    private $businessCategoryService;

    /**
     * Create a new controller instance.
     *
     * @param BusinessCategoryService $businessCategoryService
     */
    public function __construct(BusinessCategoryService $businessCategoryService)
    {

        //$this->middleware('api');
        $this->businessCategoryService = $businessCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {

        try {

            $limit = (int)(request('limit') ?? 20);
            $data = $this->businessCategoryService->paginate($limit);

            return $this->sendPaginate(new BusinessCategoryCollection($data));

        } catch (Exception $exception) {

            return $this->sendError('Server Error.', $exception);

        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {

        try {

            $data = $this->businessCategoryService->all();

            return $this->sendResource(BusinessCategoryResource::collection($data));

        } catch (Exception $exception) {

            return $this->sendError('Server Error.', $exception);
        }
    }

    /**
     * Display a listing of choices.
     *
     * @return JsonResponse
     */
    public function listOfChoices(): JsonResponse
    {

        try {

            $data = $this->businessCategoryService->listOfChoices();

            return $this->sendSimpleJson($data);

        } catch (Exception $exception) {

            return $this->sendError('Server Error.', $exception);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store()
    {

        try {

            $data = request()->all();
            $data['active'] = true;
            $storeRequest = new BusinessCategoryStoreRequest();
            $validator = Validator::make($data, $storeRequest->rules(), $storeRequest->messages());

            if ($validator->fails()) {

                return $this->sendBadRequest('Validation Error.', $validator->errors()->toArray());
            }

            $item = $this->businessCategoryService->create(request()->all());

            return $this->sendResponse($item->toArray());

        } catch (Exception $exception) {

            return $this->sendError('Server Error.', $exception);

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BusinessCategory $businessCategory
     * @return JsonResponse
     */
    public function update(BusinessCategory $businessCategory)
    {
        dd($businessCategory);
        try {

            $updateRequest = new BusinessCategoryUpdateRequest();

            $validator = Validator::make(request()->all(), $updateRequest->rules(), $updateRequest->messages());

            if ($validator->fails()) {

                return $this->sendBadRequest('Validation Error.', $validator->errors()->toArray());
            }

            $item = $this->businessCategoryService->update(request()->all(), $businessCategory);

            return $this->sendResponse($item->toArray());

        } catch (Exception $exception) {

            return $this->sendError('Server Error.', $exception);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param BusinessCategory $businessCategory
     * @return JsonResponse
     */
    public function show(BusinessCategory $businessCategory): JsonResponse
    {

        try {

            return $this->sendResource(new BusinessCategoryResource($businessCategory));

        } catch (Exception $exception) {

            return $this->sendError('Server Error.', $exception);

        }
    }

    /**
     * Remove the specified resource.
     *
     * @param BusinessCategory $businessCategory
     * @return JsonResponse
     */
    public function destroy(BusinessCategory $businessCategory): JsonResponse
    {

        try {

            $item = $this->businessCategoryService->delete($businessCategory);

            return $this->sendResponse([]);

        } catch (Exception $exception) {

            return $this->sendError('Server Error.', $exception);

        }
    }
}
