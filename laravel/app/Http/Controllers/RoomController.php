<?php

namespace App\Http\Controllers;

use App\Enums\RoomType;
use App\Models\Room;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

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
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'slug' => [
                'prohibited_unless:type,channel',
                'required_if:type,channel',
                'string',
                'max:50',
                'alpha-dash',
                'unique:rooms',
            ],
            'display_name' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', Rule::enum(RoomType::class)],
        ]);

        $new_room = Room::create($validated);

        return response()
            ->json($new_room->toResource(), 201)
            ->header('Location', route('rooms.show', $new_room->ulid));
    }

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
        $room = $roomQuery->firstOrFail();

        $is_channel = $room->type === RoomType::Channel;

        $validated = $request->validate([
            'slug' => [
                $is_channel ? 'nullable' : 'prohibited',
                'string',
                'max:50',
                'alpha-dash',
                Rule::unique('rooms')->ignore($room),
            ],
            'display_name' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:1000'],
            'type' => ['prohibited'],
        ]);

        $room->update($validated);

        return $room->toResource();
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
