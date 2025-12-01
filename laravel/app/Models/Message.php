<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasUlids;
    use SoftDeletes;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'id',
        'room_id',
        'parent_id',
        'sender_id',
        'deleted_at',
    ];

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array
     */
    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    /**
     * Get the `room` relationship.
     *
     * @return BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the ULID of the room this message was sent in.
     *
     * @return string
     */
    public function getRoomUlidAttribute(): string
    {
        return $this->room->ulid;
    }

    /**
     * Get the `parent` relationship.
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * Get the ULID of the parent message in this thread it has a parent,
     * or null otherwise.
     *
     * @return string|null
     */
    public function getParentUlidAttribute(): ?string
    {
        return $this->parent?->ulid;
    }

    /**
     * Get the `sender` relationship.
     *
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ULID of the sender of this message.
     *
     * @return string|null
     */
    public function getSenderUlidAttribute(): string
    {
        return $this->sender->ulid;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'room_id' => 'int',
            'parent_id' => 'int',
            'sender_id' => 'int',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
