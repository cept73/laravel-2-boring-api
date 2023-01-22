<?php

namespace App\Http\Controllers;

use App\Components\Activities\ActivitiesRepository;
use App\Components\Activities\ActivitiesRequest;
use App\Components\RabbitMq\RabbitMqService;
use App\Console\Commands\AppListener;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

class ActivitiesController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Load activities from external service
     *
     * If `key` specified - seek activity by key,
     * else - get random record
     *
     * @throws Configuration
     */
    public function store(): JsonResponse
    {
        $key = Request::get('key');

        return Response::json([
            'sent' => RabbitMqService::sendMessage([
                'action'    => AppListener::ACTION_FETCH_ACTIVITY,
                'key'       => $key
            ])
        ]);
    }

    /**
     * Get list of activities from database
     *
     * @return JsonResponse|Response
     */
    public function index(): Response|JsonResponse
    {
        try {
            $activitiesRequest = new ActivitiesRequest();
            $activitiesRequest->populateFromArray(Request::all());
        } catch (Throwable) {
            return Response::json(['message' => self::MESSAGE_WRONG_REQUEST], self::STATUS_WRONG_INPUT);
        }

        $activities = ActivitiesRepository::getStoredActivitiesList($activitiesRequest);

        return Response::json($activities);
    }

    /**
     * Get one activity, using specified key
     *
     * @param int $key
     * @return JsonResponse
     */
    public function show(int $key): JsonResponse
    {
        $activity = ActivitiesRepository::getStoredActivity($key);
        if ($activity === null) {
            return Response::json(['message' => self::MESSAGE_ITEM_NOT_FOUND], self::STATUS_NOT_FOUND);
        }

        return Response::json($activity);
    }

    /**
     * Delete activity from database
     *
     * @param int $key
     * @return Response|JsonResponse
     */
    public function destroy(int $key): Response|JsonResponse
    {
        $activity = ActivitiesRepository::getStoredActivity($key);
        if ($activity === null) {
            return Response::json(['message' => self::MESSAGE_ITEM_NOT_FOUND], self::STATUS_NOT_FOUND);
        }

        $activity->delete();

        return Response::json(['key' => $activity->key]);
    }
}
