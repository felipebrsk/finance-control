<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\SpendingResource;
use Illuminate\Support\Facades\{DB, Log};
use App\Contracts\Services\SpendingServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\Spending\{DetachSpendingTagsRequest, SpendingStoreRequest, SpendingUpdateRequest};

class SpendingController extends Controller
{
    /**
     * The spending service interface.
     * 
     * @var \App\Contracts\Services\SpendingServiceInterface
     */
    private $spendingServiceInterface;

    /**
     * Create a new class instance.
     * 
     * @param \App\Contracts\Services\SpendingServiceInterface $spendingServiceInterface
     * @return void
     */
    public function __construct(
        SpendingServiceInterface $spendingServiceInterface
    ) {
        $this->spendingServiceInterface = $spendingServiceInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return SpendingResource::collection(
            $this->spendingServiceInterface->allWithFilter($request)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Spending\SpendingStoreRequest  $request
     * @return \App\Http\Resources\SpendingResource
     */
    public function store(SpendingStoreRequest $request): SpendingResource
    {
        DB::beginTransaction();

        try {
            $spending = $this->spendingServiceInterface->create($request->validated());

            DB::commit();

            return SpendingResource::make($spending);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create spending', [
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
     * @return \App\Http\Resources\SpendingResource
     */
    public function show(mixed $id): SpendingResource
    {
        return SpendingResource::make(
            $this->spendingServiceInterface->findOrFail($id)->load(
                'space.currency',
                'recurring.currency',
                'import',
                'category',
                'tags',
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Spending\SpendingUpdateRequest  $request
     * @param  mixed  $id
     * @return \App\Http\Resources\SpendingResource
     */
    public function update(SpendingUpdateRequest $request, mixed $id): SpendingResource
    {
        DB::beginTransaction();

        try {
            $spending = $this->spendingServiceInterface->update($request->validated(), $id);

            DB::commit();

            return SpendingResource::make($spending);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update spending', [
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
        $this->spendingServiceInterface->delete($id);
    }

    /**
     * Detach tags from spending.
     * 
     * @param  \App\Http\Requests\Spending\DetachSpendingTagsRequest  $request
     * @param mixed $id
     * @return \App\Models\Spending
     */
    public function detachTags(DetachSpendingTagsRequest $request, mixed $id): SpendingResource
    {
        DB::beginTransaction();
        $data = $request->validated();

        try {
            $spending = $this->spendingServiceInterface->detachTags($data['tags'], $id);

            DB::commit();

            return SpendingResource::make($spending->load('tags'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to detach spending tags', [
                'message' => $e->getMessage(),
                'context' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
