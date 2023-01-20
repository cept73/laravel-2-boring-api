<?php

namespace App\Components\Activities;

use App\Components\RemoteStorage\RemoteStorageInterface;
use App\Models\Activity;

class ActivitiesService
{
    public static function loadActivity(RemoteStorageInterface $storage, ?int $keyId = null): ?Activity
    {
        $activity = ActivitiesRepository::getRemoteActivity($storage, $keyId);
        if (!$activity) {
            return null;
        }

        $activity->save();

        return $activity;
    }
}
