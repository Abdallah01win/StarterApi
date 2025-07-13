<?php

namespace App\Http\Controllers;

use App\Enums\ControllerActions;
use App\Enums\ResponseCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator as ValidationValidator;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseController extends Controller
{
    protected string $model;

    protected string $modelResource;

    protected ?string $baseRequest = null;

    protected ?string $storeRequest = null;

    protected ?string $updateRequest = null;

    protected string $resourceName;

    public function __construct()
    {
        $this->resourceName = $this->getResourceName();
    }

    /**
     * Fetch data with support for multiple query customization scenarios
     *
     * @param  Request  $request  The HTTP request
     * @param  \Closure|null  $paginationQuery  Custom query for paginated results
     * @param  \Closure|null  $listQuery  Custom query for list results
     * @param  array<string>  $listfields  Fields to select when returning a simple list
     * @param  array<mixed>  $filters  Allowed filters for Spatie Query Builder
     * @param  array<string>  $sorts  Allowed sort fields
     * @param  bool  $usePaginationQueryForList  Weather to use pagination query for list when no list query is provided
     * @param  bool  $acceptsList  Toggle to enable/disable the simple list functionality
     */
    public function readAll(
        Request $request,
        ?\Closure $paginationQuery = null,
        ?\Closure $listQuery = null,
        array $listfields = [],
        array $filters = [],
        bool $usePaginationQueryForList = false,
        array $sorts = [],
        bool $acceptsList = true
    ): JsonResponse {
        // Handle list request
        if ($acceptsList && _hasList($request)) {
            $this->authorize('list-' . $this->resourceName);

            if ($listQuery !== null) {
                $baseListQuery = $this->model::query();
                $query         = QueryBuilder::for($listQuery($baseListQuery));
            } elseif ($paginationQuery !== null && $usePaginationQueryForList) {
                $baseQuery = $this->model::query();
                $query     = QueryBuilder::for($paginationQuery($baseQuery));
            } else {
                $query = QueryBuilder::for($this->model);
            }

            return response()->json($query->select($listfields)->get());
        }

        // Handle paginated request with filters and sorting
        $this->authorize('view-' . $this->resourceName);

        if ($paginationQuery !== null) {
            $baseQuery = $this->model::query();
            $query     = QueryBuilder::for($paginationQuery($baseQuery));
        } else {
            $query = QueryBuilder::for($this->model);
        }

        $items = $query->allowedFilters($filters)
            ->allowedSorts([...$sorts, 'created_at'])
            ->defaultSort('-created_at')
            ->paginate(_paginatePages());

        return $this->modelResource::collection($items)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create-' . $this->resourceName);

        $validator = $this->validateRequest($request, ControllerActions::STORE);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], ResponseCode::UNPROCESSABLE_CONTENT);
        }

        $this->model::create($validator->validated());

        return response()->json(true, ResponseCode::CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $this->authorize('update-' . $this->resourceName);

        try {
            $model     = $this->model::findOrFail($id);
            $validator = $this->validateRequest($request, ControllerActions::UPDATE);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], ResponseCode::UNPROCESSABLE_CONTENT);
            }

            $model->update($validator->validated());

            return response()->json(true, ResponseCode::ACCEPTED);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], ResponseCode::NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->authorize('delete-' . $this->resourceName);

        try {
            $model = $this->model::findOrFail($id);
            $model->delete();

            return response()->json(true, ResponseCode::NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(false, ResponseCode::NOT_FOUND);
        }
    }

    /**
     * Get the pluralized resource name for authorization.
     */
    protected function getResourceName(): string
    {
        return Str::plural(Str::kebab(class_basename($this->model)));
    }

    /**
     * Resolve the request class for the given action.
     */
    protected function getRequestClass(string $action): mixed
    {
        $instance = match ($action) {
            ControllerActions::STORE  => $this->storeRequest  ?? $this->baseRequest,
            ControllerActions::UPDATE => $this->updateRequest ?? $this->baseRequest,
            default  => throw new \RuntimeException("Invalid action: {$action}")
        };

        return new $instance;
    }

    /**
     * Validate the request for the given action.
     */
    protected function validateRequest(Request $request, string $action): ValidationValidator
    {
        $requestClass = $this->getRequestClass($action);

        return Validator::make($request->all(), $requestClass->rules());
    }
}
