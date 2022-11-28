<?php

namespace App\Enum;

enum EventAction: String
{
    case info = 'info';
    case create = 'create';
    case delete = 'delete';
    case changeAmount = 'changeAmount';
    case userCreated = 'userCreated';
}
