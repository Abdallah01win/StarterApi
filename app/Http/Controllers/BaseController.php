<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

abstract class BaseController extends Controller
{
    protected string $model;

    protected string $baseRequest;

    protected string $storeRequest;

    protected string $updateRequest;

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->authorize('create-' . $this->getResourceName());

        $request = $this->resolveRequestClass('store');

        $this->model::create($request->validated());

        return response()->json(true, ResponseCode::CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): JsonResponse
    {
        $this->authorize('update-' . $this->getResourceName());

        $model = $this->model::findOrFail($id);

        $request = $this->resolveRequestClass('update');

        $model->update($request->validated());

        return response()->json(true, ResponseCode::ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Model $model): Response
    {
        $this->authorize('delete-' . $this->getResourceName());

        $model->delete();

        return response()->noContent();
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
