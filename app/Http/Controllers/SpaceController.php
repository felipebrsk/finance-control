<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\SpaceResource;
use Illuminate\Support\Facades\{DB, Log};
use App\Contracts\Services\SpaceServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\Space\{DetachSpaceTagsRequest, SpaceStoreRequest, SpaceUpdateRequest};

class SpaceController extends Controller
{
    /**
     * The earning service interface.
     * 
     * @var \App\Contracts\Services\SpaceServiceInterface
     */
    private $spaceServiceInterface;

    /**
     * Create a new class instance.
     * 
     * @param \App\Contracts\Services\SpaceServiceInterface $spaceServiceInterface
     * @return void
     */
    public function __construct(
        SpaceServiceInterface $spaceServiceInterface
    ) {
        $this->spaceServiceInterface = $spaceServiceInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return SpaceResource::collection(
            $this->spaceServiceInterface->allAuthUserSpacesWithFilter($request),
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\SpaceResource
     */
    public function store(SpaceStoreRequest $request): SpaceResource
    {
        DB::beginTransaction();

        try {
            $space = $this->spaceServiceInterface->create($request->validated())->load('tags');

            DB::commit();

            return SpaceResource::make($space);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create space', [
                'message' => $e->getMessage(),
                'context' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed  $id
     * @return \App\Http\Resources\SpaceResource
     */
    public function show(mixed $id): SpaceResource
    {
        return SpaceResource::make(
            $this->spaceServiceInterface->findOrFail($id)->load(
                'tags',
                'currency',
                'categories',
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $id
     * @return \App\Http\Resources\SpaceResource
     */
    public function update(SpaceUpdateRequest $request, mixed $id): SpaceResource
    {
        DB::beginTransaction();

        try {
            $space = $this->spaceServiceInterface->update($request->validated(), $id)->load('tags');

            DB::commit();

            return SpaceResource::make($space);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update space', [
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
        $this->spaceServiceInterface->delete($id);
    }

    /**
     * Detach tags from space.
     * 
     * @param  \App\Http\Requests\Space\DetachSpaceTagsRequest  $request
     * @param mixed $id
     * @return \App\Http\Resources\SpaceResource
     */
    public function detachTags(DetachSpaceTagsRequest $request, mixed $id): SpaceResource
    {
        DB::beginTransaction();
        $data = $request->validated();

        try {
            $space = $this->spaceServiceInterface->detachTags($data['tags'], $id);

            DB::commit();

            return SpaceResource::make($space->load('tags'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to detach space tags', [
                'message' => $e->getMessage(),
                'context' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
