<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ActivityResource;
use App\Contracts\Services\ActivityServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActivityController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(
        Request $request,
        ActivityServiceInterface $activityServiceInterface
    ): AnonymousResourceCollection {
        return ActivityResource::collection(
            $activityServiceInterface->allWithFilter($request)
        );
    }
}
