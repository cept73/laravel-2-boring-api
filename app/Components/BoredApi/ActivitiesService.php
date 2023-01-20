<?php

namespace App\Components\BoredApi;

use App\Models\Activity;

class ActivitiesService
{
    public static function loadActivity($keyId = null): ?Activity
    {
        $activity = ActivitiesRepository::getRemoteActivity(new BoredApiRemoteStorage(), $keyId);
        if (!$activity) {
            return null;
        }

        $activity->save();
        return $activity;
    }
}
