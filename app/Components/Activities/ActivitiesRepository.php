<?php

namespace App\Components\Activities;

use App\Components\RemoteStorage\RemoteStorageInterface;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ActivitiesRepository
{
    public static function getStoredActivity(?int $key): ?Activity
    {
        return Activity::query()->where(['key' => $key])->get()->first();
    }

    public static function getStoredActivityOrFail(?int $key): Activity
    {
        $activity = self::getStoredActivity($key);

        if (empty($activity)) {
            throw new NotFoundResourceException();
        }

        return $activity;
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

    public static function getStoredActivitiesList(ActivitiesRequest $activitiesRequest): LengthAwarePaginator
    {
        return Activity::query()
            ->where(Activity::getWhereConditions($activitiesRequest->asArray()))
            ->paginate($activitiesRequest->onPage());
    }

    public static function getStoredCount(): int
    {
        return Activity::query()->count();
    }
}
