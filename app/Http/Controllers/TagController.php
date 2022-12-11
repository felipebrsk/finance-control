<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\{DB, Log};
use App\Contracts\Services\TagServiceInterface;
use App\Http\Requests\Tag\{TagStoreRequest, TagUpdateRequest};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    /**
     * The tag service interface.
     *
     * @var \App\Contracts\Services\TagServiceInterface
     */
    private $tagServiceInterface;

    /**
     * Create a new class instance.
     *
     * @var \App\Contracts\Services\TagServiceInterface $tagServiceInterface
     * @return void
     */
    public function __construct(
        TagServiceInterface $tagServiceInterface
    ) {
        $this->tagServiceInterface = $tagServiceInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return TagResource::collection(
            $this->tagServiceInterface->allWithFilter($request)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Tag\TagStoreRequest  $request
     * @return \App\Http\Resources\TagResource
     */
    public function store(TagStoreRequest $request): TagResource
    {
        DB::beginTransaction();

        try {
            $tag = $this->tagServiceInterface->create($request->validated());

            DB::commit();

            return TagResource::make($tag);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create tag', [
                'message' => $e->getMessage(),
                'context' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Tag\TagUpdateRequest  $request
     * @param  mixed  $id
     * @return \App\Http\Resources\TagResource
     */
    public function update(TagUpdateRequest $request, mixed $id): TagResource
    {
        DB::beginTransaction();

        try {
            $tag = $this->tagServiceInterface->update($request->validated(), $id);

            DB::commit();

            return TagResource::make($tag);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create tag', [
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
        $this->tagServiceInterface->delete($id);
    }
}
