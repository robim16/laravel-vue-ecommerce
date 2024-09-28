<?php

namespace App\Enums;


/**
 * Class OrderStatus
 *
 * @package App\Enums
 */
enum OrderStatus: string
{
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Completed = 'complete';
}
