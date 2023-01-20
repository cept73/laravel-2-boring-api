<?php /** @noinspection HttpUrlsUsage */

namespace App\Components\RemoteStorage;

use Illuminate\Support\Facades\Http;

class BoredApiRemoteStorage implements RemoteStorageInterface
{
    public const URL = 'http://www.boredapi.com/';

    public function load($key)
    {
        $boredUrl    = self::URL . 'api/activity/';
        $boredParams = $key ? ['key' => $key] : null;

        return Http::get($boredUrl, $boredParams)->json();
    }
}
