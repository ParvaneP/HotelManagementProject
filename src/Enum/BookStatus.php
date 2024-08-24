<?php

namespace App\Enum;

enum BookStatus: string
{
    case Checkedin = 'checked-in';
    case Booked = 'booked';
    case Checkedout = 'checked-out';
    case Cancelled = 'cancelled';
}