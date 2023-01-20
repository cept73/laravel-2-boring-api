<?php

namespace App\Components\PostponedActions;

use Psr\Log\LoggerInterface;

interface ActionInterface
{
    public static function do(LoggerInterface $logger, $data);
}
