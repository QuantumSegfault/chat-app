<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
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
                        'parent_id',
                        'deleted_at',
                    ),
                'messages.sender' => fn($query) => $query->select(
                    'id',
                    'ulid',
                    'username',
                ),
                'messages.parent' => fn($query) => $query->select('id', 'ulid'),
            ])
            ->firstOrFail(['id', 'ulid']);

        return $room->messages->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Builder $roomQuery)
    {
        $room = $roomQuery->firstOrFail(['id', 'ulid']);
        $sender = User::findOrFail(1, ['id', 'ulid']);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:4096'],
            'parent' => [
                'nullable',
                'string',
                'min:26',
                'max:26',
                'exists:messages,ulid',
            ],
        ]);

        $parent_msg = null;
        if (!empty($validated['parent'])) {
            $parent_msg = Message::where(
                'ulid',
                $validated['parent'],
            )->firstOrFail('id');
        }

        $new_msg = $room->messages()->create([
            'body' => $validated['body'],
            'parent_id' => $parent_msg?->id,
            'sender_id' => $sender->id,
        ]);

        return response()
            ->json($new_msg->toResource(), 201)
            ->header('Location', route('messages.show', $new_msg->ulid));
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message): JsonResource
    {
        $message->load('sender');
        $message->load('parent');
        return $message->toResource();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        $validated = $request->validate([
            'body' => ['string', 'max:4096'],
            'parent' => ['prohibited'],
        ]);

        $message->update(['body' => $validated['body']]);

        return $message->toResource();
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
