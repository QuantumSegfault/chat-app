<?php

use App\Enums\RoomType;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\Uid\Ulid;

Route::get('rooms', function (Request $request) {
    $rooms = Room::all(['ulid', 'slug', 'display_name', 'type']);

    return $rooms;
});

Route::get('rooms/{ident}', function (string $ident) {
    if (!Ulid::isValid($ident)) {
        $room_ulid = Room::where('slug', $ident)->valueOrFail('ulid');
        return redirect(
            route(Route::currentRouteName(), ['ident' => $room_ulid]),
            308,
        );
    }

    return Room::where('ulid', $ident)->firstOrFail();
})->name('room.get');

Route::get('rooms/{ident}/messages', function (string $ident) {
    if (!Ulid::isValid($ident)) {
        $room_ulid = Room::where('slug', $ident)->valueOrFail('ulid');
        return redirect(
            route(Route::currentRouteName(), ['ident' => $room_ulid]),
            308,
        );
    }

    $room = Room::with([
        'messages' => fn($query) => $query->select('*'),
        'messages.sender' => fn($query) => $query->select('id', 'ulid'),
    ])
        ->where('ulid', $ident)
        ->firstOrFail(['id', 'ulid']);
    return $room->messages;
})->name('room.messages.index');

Route::get('messages', function () {
    $messages = Message::with([
        'room' => fn($query) => $query->select('id', 'ulid'),
    ])->get();
    return $messages;
});

Route::get('messages/{ulid}', function (string $ulid) {
    return Message::with([
        'room' => fn($query) => $query->select('id', 'ulid'),
        'sender' => fn($query) => $query->select('id', 'ulid'),
    ])
        ->where('ulid', $ulid)
        ->firstOrFail();
});

Route::get('users/{ident}', function (string $ident) {
    if (!Ulid::isValid($ident)) {
        $user_ulid = User::where('username', $ident)->valueOrFail('ulid');
        return redirect(
            route(Route::currentRouteName(), ['ident' => $user_ulid]),
            308,
        );
    }

    return User::where('ulid', $ident)->firstOrFail();
})->name('user.get');
