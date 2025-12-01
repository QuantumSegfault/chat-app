<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        $rooms = Room::all([
            'ulid',
            'slug',
            'display_name',
            'type',
            'created_at',
            'archived_at',
        ]);

        return $rooms->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Builder $roomQuery): JsonResource
    {
        $room = $roomQuery->firstOrFail([
            'ulid',
            'slug',
            'display_name',
            'description',
            'type',
            'created_at',
            'archived_at',
        ]);

        return $room->toResource();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Builder $roomQuery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Builder $roomQuery)
    {
        $roomQuery->firstOrFail()->delete();

        return response()->noContent();
    }
}
