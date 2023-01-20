<?php

namespace App\Observers;

use App\Components\Activities\ActivitiesRepository;
use App\Components\RabbitMq\RabbitMqService;
use App\Console\Commands\AppListener;
use App\Models\Activity;
use Bschmitt\Amqp\Exception\Configuration;

class ActivitiesObserver
{
    /**
     * Action on any activity changes
     *
     * @param Activity $activity
     * @return void
     * @throws Configuration
     * @noinspection PhpUnusedParameterInspection
     */
    private function actionOnChanges(Activity $activity): void
    {
        RabbitMqService::sendMessage([
            'action'    => AppListener::ACTION_REPORT,
            'count'     => ActivitiesRepository::getStoredCount()
        ]);
    }

    /**
     * Handle the Activity "created" event.
     *
     * @param Activity $activity
     * @return void
     * @throws Configuration
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
     * @throws Configuration
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
     * @throws Configuration
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
     * @throws Configuration
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
     * @throws Configuration
     */
    public function forceDeleted(Activity $activity): void
    {
        $this->actionOnChanges($activity);
    }
}
