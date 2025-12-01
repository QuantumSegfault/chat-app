<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\RoomController;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing;
use Symfony\Component\Uid\Ulid;

Route::bind('room', function (string $id, Routing\Route $route): Builder {
    if (!Ulid::isValid($id)) {
        $room_ulid = Room::where('slug', $id)->valueOrFail('ulid');

        $parameters = $route->parameters();
        $parameters['room'] = $room_ulid;

        throw new HttpResponseException( // to immediately stop and redirect
            redirect()->route($route->getName(), $parameters, 308),
        );
    }

    return Room::where('ulid', $id);
});

Route::apiResource('rooms', RoomController::class);
Route::apiResource('rooms.messages', MessageController::class)->shallow();
