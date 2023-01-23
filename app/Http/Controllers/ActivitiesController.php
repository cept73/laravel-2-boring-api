<?php

namespace App\Http\Controllers;

use App\Components\Activities\ActivitiesRepository;
use App\Components\Activities\ActivitiesRequest;
use App\Components\RabbitMq\RabbitMqService;
use App\Console\Commands\AppListener;
use App\Models\Activity;
use Bschmitt\Amqp\Exception\Configuration;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function store(Request $request): JsonResponse
    {
        return Response::json([
            'sent' => RabbitMqService::sendMessage([
                'action'    => AppListener::ACTION_FETCH_ACTIVITY,
                'key'       => $request->get('key')
            ])
        ]);
    }

    /**
     * Get list of activities from database
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function index(Request $request): Response|JsonResponse
    {
        try {
            $activitiesRequest = new ActivitiesRequest();
            $activitiesRequest->populateFromArray($request->toArray());
            $activities = ActivitiesRepository::getStoredActivitiesList($activitiesRequest);

            return Response::json($activities);
        } catch (Throwable) {
            return $this->wrongRequestResponse();
        }
    }

    /**
     * Get one activity, using specified key
     *
     * @param Activity $activity
     * @return JsonResponse
     */
    public function show(Activity $activity): JsonResponse
    {
        return Response::json($activity);
    }

    /**
     * Delete activity from database
     *
     * @param Activity $activity
     * @return Response|JsonResponse
     */
    public function destroy(Activity $activity): Response|JsonResponse
    {
        $activity->delete();

        return Response::json(['key' => $activity->key]);
    }
}
