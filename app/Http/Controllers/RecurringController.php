<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\RecurringResource;
use Illuminate\Support\Facades\{DB, Log};
use App\Contracts\Services\RecurringServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\Recurring\{
    DetachRecurringTagsRequest,
    RecurringStoreRequest,
    RecurringUpdateRequest
};

class RecurringController extends Controller
{
    /**
     * The recurring service interface.
     * 
     * @var \App\Contracts\Services\RecurringServiceInterface
     */
    private $recurringServiceInterface;

    /**
     * Create a new class instance.
     * 
     * @param \App\Contracts\Services\RecurringServiceInterface $recurringServiceInterface
     * @return void
     */
    public function __construct(
        RecurringServiceInterface $recurringServiceInterface
    ) {
        $this->recurringServiceInterface = $recurringServiceInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return RecurringResource::collection(
            $this->recurringServiceInterface->allWithFilter($request)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Recurring\RecurringStoreRequest  $request
     * @return \App\Http\Resources\RecurringResource
     */
    public function store(RecurringStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $recurring = $this->recurringServiceInterface->create($request->validated());

            DB::commit();

            return RecurringResource::make($recurring);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create recurring', [
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
     * @return \App\Http\Resources\RecurringResource
     */
    public function show(mixed $id): RecurringResource
    {
        return RecurringResource::make(
            $this->recurringServiceInterface->findOrFail($id)->load(
                'tags',
                'space',
                'category',
                'currency',
                'earnings',
                'spendings',
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Recurring\RecurringUpdateRequest  $request
     * @param  mixed  $id
     * @return \App\Http\Resources\RecurringResource
     */
    public function update(RecurringUpdateRequest $request, mixed $id)
    {
        DB::beginTransaction();

        try {
            $recurring = $this->recurringServiceInterface->update($request->validated(), $id);

            DB::commit();

            return RecurringResource::make($recurring->load('tags'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update recurring', [
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
        $this->recurringServiceInterface->delete($id);
    }

    /**
     * Detach tags from space.
     * 
     * @param  \App\Http\Requests\Recurring\DetachRecurringTagsRequest  $request
     * @param mixed $id
     * @return \App\Http\Resources\RecurringResource
     */
    public function detachTags(DetachRecurringTagsRequest $request, mixed $id): RecurringResource
    {
        DB::beginTransaction();
        $data = $request->validated();

        try {
            $recurring = $this->recurringServiceInterface->detachTags($data['tags'], $id);

            DB::commit();

            return RecurringResource::make($recurring->load('tags'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to detach recurring tags', [
                'message' => $e->getMessage(),
                'context' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
