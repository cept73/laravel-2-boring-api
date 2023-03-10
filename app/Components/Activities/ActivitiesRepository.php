<?php

namespace App\Components\Activities;

use App\Components\RemoteStorage\RemoteStorageInterface;
use App\Models\Activity;
use App\Models\ActivitySearch;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActivitiesRepository
{
    public static function getStoredActivity(?int $key): ?Activity
    {
        return Activity::query()->where(['key' => $key])->get()->first();
    }

    /**
     * Get activity
     *
     * keyId - key_id or null for random
     */
    public static function getRemoteActivity(RemoteStorageInterface $remoteStorage, ?int $key = null): ?Activity
    {
        $loadedJson = $remoteStorage->load($key);

        $key = $loadedJson['key'] ?? null;
        if (!$key) {
            return null;
        }

        $activity = self::getStoredActivity($key) ?: new Activity();
        $activity->populateFromJson($loadedJson);
        $activity->loaded_at = Carbon::now();

        return $activity;
    }

    public static function getStoredActivitiesList(ActivitySearch $activitySearch): LengthAwarePaginator
    {
        return Activity::query()
            ->where($activitySearch->toArray())
            ->paginate($activitySearch->getAttribute('onPage'));
    }

    public static function getStoredCount(): int
    {
        return Activity::query()->count();
    }
}
