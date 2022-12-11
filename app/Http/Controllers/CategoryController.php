<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\{DB, Log};
use App\Contracts\Services\CategoryServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\Category\{CategoryStoreRequest, CategoryUpdateRequest};

class CategoryController extends Controller
{
    /**
     * The category service interface.
     *
     * @var \App\Contracts\Services\CategoryServiceInterface
     */
    private $categoryServiceInterface;

    /**
     * Create a new class instance.
     *
     * @param \App\Contracts\Services\CategoryServiceInterface $categoryServiceInterface
     * @return void
     */
    public function __construct(
        CategoryServiceInterface $categoryServiceInterface
    ) {
        $this->categoryServiceInterface = $categoryServiceInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return CategoryResource::collection(
            $this->categoryServiceInterface->allWithFilter($request)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Category\CategoryStoreRequest  $request
     * @return \App\Http\Resources\CategoryResource
     */
    public function store(CategoryStoreRequest $request): CategoryResource
    {
        DB::beginTransaction();

        try {
            $category = $this->categoryServiceInterface->create($request->validated());

            DB::commit();

            return CategoryResource::make($category);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create category', [
                'message' => $e->getMessage(),
                'context' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Category\CategoryUpdateRequest  $request
     * @param  mixed  $id
     * @return \App\Http\Resources\CategoryResource
     */
    public function update(CategoryUpdateRequest $request, mixed $id)
    {
        DB::beginTransaction();

        try {
            $category = $this->categoryServiceInterface->update($request->validated(), $id);

            DB::commit();

            return CategoryResource::make($category);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update category', [
                'message' => $e->getMessage(),
                'context' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  mixed  $id
     * @return void
     */
    public function destroy(mixed $id): void
    {
        $this->categoryServiceInterface->delete($id);
    }
}
