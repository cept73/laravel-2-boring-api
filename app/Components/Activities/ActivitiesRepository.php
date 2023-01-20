<?php

namespace App\Components\Activities;

use App\Components\RemoteStorage\RemoteStorageInterface;
use App\Models\Activity;
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

        if ($key = $loadedJson['key'] ?? null) {
            $activity = self::getStoredActivity($key) ?: new Activity();
            $activity->populateFromJson($loadedJson);
            $activity->loaded_at = Carbon::now();

            return $activity;
        }

        return null;
    }

    public static function getStoredActivitiesList(ActivitiesRequest $activitiesRequest): LengthAwarePaginator
    {
        $where = [];
        foreach (Activity::FILTER_PROPERTIES as $filterKey => $dbProperty) {
            if (is_int($filterKey)) {
                $filterKey = $dbProperty;
            }
            if ($filterValue = $activitiesRequest->get($filterKey)) {
                $where[$dbProperty] = $filterValue;
            }
        }

        return Activity::query()
            ->where($where)
            ->paginate($activitiesRequest->onPage());
    }

    public static function getStoredCount(): int
    {
        return Activity::all()->count();
    }
}
