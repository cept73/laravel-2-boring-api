<?php /** @noinspection PhpUnused */

namespace App\Components\PostponedActions;

use App\Components\Console\Console;
use App\Components\Logger\LoggerTrait;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Psr\Log\LoggerInterface;

class ReportAdminAction implements ActionInterface
{
    use LoggerTrait;

    public static function do(LoggerInterface $logger, $data)
    {
        $count = $data['count'];
        self::log($logger, "Activities count: $count", Console::COLOR_GREEN);

        if ($adminEmail = env('ADMIN_EMAIL')) {
            $templateParams = [
                'name'  => env('ADMIN_NAME'),
                'count' => $count
            ];

            Mail::send('alert.mail', $templateParams, function (Message $message) use ($adminEmail) {
                $message
                    ->to($adminEmail, env('ADMIN_NAME'))
                    ->subject(env('ALERT_SUBJECT'))
                    ->from(env('ALERT_FROM_EMAIL'), env('ALERT_FROM_NAME'));
            });
        }
    }
}
