<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    public const STATUS_NOT_FOUND = 404;
    public const STATUS_WRONG_INPUT = 400;

    public const MESSAGE_KEY_NOT_FOUND = 'Key not found';
    public const MESSAGE_WRONG_REQUEST = 'Wrong request';
}
