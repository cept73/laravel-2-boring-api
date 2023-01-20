<?php /** @noinspection PhpUnused */

namespace App\Components\PostponedActions;

use App\Components\Activities\ActivitiesService;
use App\Components\Logger\LoggerTrait;
use App\Components\RemoteStorage\BoredApiRemoteStorage;
use Psr\Log\LoggerInterface;

class FetchActivitiesAction implements ActionInterface
{
    use LoggerTrait;

    public static function do(LoggerInterface $logger, $data)
    {
        $key = $data['key'];

        self::log($logger, 'Download: ' . ($key ?? 'random'));
        ActivitiesService::loadActivity(new BoredApiRemoteStorage(), $key);
    }
}
