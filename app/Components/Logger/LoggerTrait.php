<?php

namespace App\Components\Logger;

use App\Components\Console\Console;
use Carbon\Carbon;
use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    public static function log(LoggerInterface $logger, $message, $color = null): void
    {
        $dateTimeNow    = Carbon::now();
        $messageText    = "$dateTimeNow: $message";
        $messageColored = Console::COLOR_CYAN . $dateTimeNow . ':' . Console::COLOR_WHITE . ' '
            . ($color ? "$color$message" . Console::COLOR_WHITE : $message);

        print $messageColored . PHP_EOL;
        $logger->info($messageText);
    }
}
