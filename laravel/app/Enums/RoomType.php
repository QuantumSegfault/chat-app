<?php

namespace App\Enums;

enum RoomType: string
{
    case Channel = 'channel';
    case Group = 'group';
    case DM = 'dm';
}
