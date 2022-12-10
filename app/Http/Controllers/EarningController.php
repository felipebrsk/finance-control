<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\EarningResource;
use Illuminate\Support\Facades\{DB, Log};
use App\Contracts\Services\EarningServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\Earning\{DetachEarningTagsRequest, EarningStoreRequest, EarningUpdateRequest};

class EarningController extends Controller
{
    /**
     * The earning service interface.
     * 
     * @var \App\Contracts\Services\EarningServiceInterface
     */
    private $earningServiceInterface;

    /**
     * Create a new class instance.
     * 
     * @param \App\Contracts\Services\EarningServiceInterface $earningServiceInterface
     * @return void
     */
    public function __construct(
        EarningServiceInterface $earningServiceInterface
    ) {
        $this->earningServiceInterface = $earningServiceInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return EarningResource::collection(
            $this->earningServiceInterface->allWithFilter($request)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Earning\EarningStoreRequest  $request
     * @return \App\Http\Resources\EarningResource
     */
    public function store(EarningStoreRequest $request): EarningResource
    {
        DB::beginTransaction();

        try {
            $earning = $this->earningServiceInterface->create($request->validated());

            DB::commit();

            return EarningResource::make($earning);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create earning', [
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
     * @return \App\Http\Resources\EarningResource
     */
    public function show(mixed $id): EarningResource
    {
        return EarningResource::make(
            $this->earningServiceInterface->findOrFail($id)->load(
                'space.currency',
                'recurring.currency',
                'category',
                'tags'
            ),
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Earning\EarningUpdateRequest  $request
     * @param  mixed  $id
     * @return \App\Http\Resources\EarningResource
     */
    public function update(EarningUpdateRequest $request, mixed $id): EarningResource
    {
        DB::beginTransaction();

        try {
            $earning = $this->earningServiceInterface->update($request->validated(), $id);

            DB::commit();

            return EarningResource::make($earning);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update earning', [
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
        $this->earningServiceInterface->delete($id);
    }

    /**
     * Detach tags from earning.
     * 
     * @param  \App\Http\Requests\Earning\DetachEarningTagsRequest  $request
     * @param mixed $id
     * @return \App\Http\Resources\EarningResource
     */
    public function detachTags(DetachEarningTagsRequest $request, mixed $id): EarningResource
    {
        DB::beginTransaction();
        $data = $request->validated();

        try {
            $earning = $this->earningServiceInterface->detachTags($data['tags'], $id);

            DB::commit();

            return EarningResource::make($earning->load('tags'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to detach earning tags', [
                'message' => $e->getMessage(),
                'context' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
