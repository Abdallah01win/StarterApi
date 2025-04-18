<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseController extends Controller
{
    protected string $model;

    protected string $modelResource;

    protected ?string $baseRequest;

    protected ?string $storeRequest;

    protected ?string $updateRequest;

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
     * @param  array  $listfields  Fields to select when returning a simple list
     * @param  array  $filters  Allowed filters for Spatie Query Builder
     * @param  array  $sorts  Allowed sort fields
     * @param  bool $usePaginationQueryForList Weather to use pagination query for list when no list query is provided
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
    public function store(): JsonResponse
    {
        $this->authorize('create-' . $this->resourceName);

        $request = $this->resolveRequestClass('store');

        $this->model::create($request->validated());

        return response()->json(true, ResponseCode::CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): JsonResponse
    {
        $this->authorize('update-' . $this->resourceName);

        try {
            $model   = $this->model::findOrFail($id);
            $request = $this->resolveRequestClass('update');
            $model->update($request->validated());

            return response()->json(true, ResponseCode::ACCEPTED);
        } catch (\Exception $e) {
            return response()->json(false, ResponseCode::NOT_FOUND);
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
     * Resolve the request class for the given action.
     */
    protected function resolveRequestClass(string $action): FormRequest
    {
        if (! $this->baseRequest && (! $this->storeRequest || ! $this->updateRequest)) {
            throw new \RuntimeException("Request class not found for {$action} action");
        }

        if ($action === 'store') {
            return app($this->storeRequest ?? $this->baseRequest);
        } elseif ($action === 'update') {
            return app($this->updateRequest ?? $this->baseRequest);
        }

        throw new \RuntimeException("Invalid action: {$action}");
    }

    /**
     * Get the pluralized resource name for authorization.
     */
    protected function getResourceName(): string
    {
        return Str::plural(Str::kebab(class_basename($this->model)));
    }
}
