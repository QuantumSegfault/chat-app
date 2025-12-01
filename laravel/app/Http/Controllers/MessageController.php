<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $roomQuery): ResourceCollection
    {
        $room = $roomQuery
            ->with([
                'messages' => fn($query) => $query
                    ->withTrashed()
                    ->select(
                        'ulid',
                        'body',
                        'created_at',
                        'updated_at',
                        'room_id',
                        'sender_id',
                        'deleted_at',
                    ),
                'messages.sender' => fn($query) => $query->select(
                    'id',
                    'ulid',
                    'username',
                ),
            ])
            ->firstOrFail(['id', 'ulid']);

        return $room->messages->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message): JsonResource
    {
        $message->load('sender');
        return $message->toResource();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $message->delete();

        return response()->noContent();
    }
}
