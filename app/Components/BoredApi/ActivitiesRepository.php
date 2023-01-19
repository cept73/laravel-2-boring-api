<?php

namespace App\Components\BoredApi;

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class ActivitiesRepository
{
    /**
     * Get activity
     *
     * keyId - key_id or null for random
     */
    public static function getActivity(?int $key = null): ?Activity
    {
        $boredUrl    = BoredApi::URL . 'api/activity/';
        $boredParams = $key ? ['key' => $key] : null;

        $loadedJson  = Http::get($boredUrl, $boredParams)->json();

        if ($key = $loadedJson['key'] ?? null) {
            /** @var ?Activity $activity */
            $activity = Activity::query()->find(['key' => $key])->first() ?: new Activity();
            foreach ($loadedJson as $propKey => $propValue) {
                $activity->$propKey = $propValue;
            }
            $activity->loaded_at = Carbon::now();

            return $activity;
        }

        return null;
    }

    public static function getActivitiesList(ActivitiesRequest $activitiesRequest): LengthAwarePaginator
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

    public static function getActiveCount(): int
    {
        return Activity::all()->count();
    }
}
