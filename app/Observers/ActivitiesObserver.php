<?php

namespace App\Observers;

use App\Components\Activities\ActivitiesRepository;
use App\Models\Activity;
use Illuminate\Support\Facades\Log;

class ActivitiesObserver
{
    /**
     * Action on any activity changes
     *
     * @param Activity $activity
     * @return void
     */
    private function actionOnChanges(Activity $activity): void
    {
        Log::warning('ACTIVITIES COUNT', [
            'keyChanged'    => $activity->key,
            'overallCount'  => ActivitiesRepository::getStoredCount()
        ]);
    }

    /**
     * Handle the Activity "created" event.
     *
     * @param Activity $activity
     * @return void
     */
    public function created(Activity $activity):void
    {
        $this->actionOnChanges($activity);
    }

    /**
     * Handle the Activity "updated" event.
     *
     * @param Activity $activity
     * @return void
     */
    public function updated(Activity $activity):void
    {
        $this->actionOnChanges($activity);
    }

    /**
     * Handle the Activity "deleted" event.
     *
     * @param Activity $activity
     * @return void
     */
    public function deleted(Activity $activity):void
    {
        $this->actionOnChanges($activity);
    }

    /**
     * Handle the Activity "restored" event.
     *
     * @param Activity $activity
     * @return void
     * @noinspection PhpUnused
     */
    public function restored(Activity $activity): void
    {
        $this->actionOnChanges($activity);
    }

    /**
     * Handle the Activity "force deleted" event.
     *
     * @param Activity $activity
     * @return void
     * @noinspection PhpUnused
     */
    public function forceDeleted(Activity $activity): void
    {
        $this->actionOnChanges($activity);
    }
}
