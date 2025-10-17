<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\EmotionalCheckinService;
use App\Http\Resources\Admin\Index\EmotionalCheckinResource;
use App\Http\Requests\Admin\Index\IndexEmotionalCheckinRequest;
use App\Http\Requests\Admin\Store\StoreEmotionalCheckinRequest;
use App\Http\Requests\Admin\Update\UpdateEmotionalCheckinRequest;
use App\Http\Resources\Admin\Detail\DetailEmotionalCheckinResource;

class EmotionalCheckinsController extends Controller
{
    protected $emotionalCheckinService;

    public function __construct(EmotionalCheckinService $emotionalCheckinService)
    {
        $this->emotionalCheckinService = $emotionalCheckinService;

        // Middleware permission (optional, kalau kamu pakai Spatie Permission)
        $this->middleware(['permission:index emotional checkin'], ['only' => ['index']]);
        $this->middleware(['permission:get emotional checkin'], ['only' => ['get']]);
        $this->middleware(['permission:create emotional checkin'], ['only' => ['store']]);
        $this->middleware(['permission:update emotional checkin'], ['only' => ['update']]);
        $this->middleware(['permission:delete emotional checkin'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexEmotionalCheckinRequest $request)
    {
        $query = $request->validated();

        $result = $this->emotionalCheckinService->searchEmotionalCheckin(['user','contact'], 10, $query['search'] ?? null);

        return $this->emotionalCheckinService->successPaginate(EmotionalCheckinResource::collection($result),200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmotionalCheckinRequest $request)
    {
        $data = $request->validated();

        $result = $this->emotionalCheckinService->createEmotionalCheckin($data);

        $result->load(['user', 'contact']);

        return $this->emotionalCheckinService->success(new DetailEmotionalCheckinResource($result),200,'Created Emotional Check-in Successfully'
        );
    }

    /**
     * Display the specified resource.
     */
    public function get(string $uuid)
    {
        $result = $this->emotionalCheckinService->findByUuidWithRelation($uuid, ['user', 'contact']);

        return $this->emotionalCheckinService->success(
            new DetailEmotionalCheckinResource($result),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmotionalCheckinRequest $request, string $uuid)
    {
        $data = $request->validated();

        $result = $this->emotionalCheckinService->updateEmotionalCheckin($uuid, $data);

        $result->load(['user', 'contact']);

        return $this->emotionalCheckinService->success(new DetailEmotionalCheckinResource($result),200,'Updated Emotional Check-in Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $this->emotionalCheckinService->destroyByUuid($uuid);

        return $this->emotionalCheckinService->success('', 200, 'Deleted Emotional Check-in Successfully');
    }
}
