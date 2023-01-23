<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    public const MESSAGE_ITEM_NOT_FOUND = 'Item not found';
    public const MESSAGE_WRONG_REQUEST  = 'Wrong request';
}
