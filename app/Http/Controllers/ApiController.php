<?php

namespace App\Http\Controllers;

use App\Components\BoredApi\ActivitiesRepository;
use App\Components\BoredApi\ActivitiesRequest;
use App\Components\BoredApi\ActivitiesService;
use App\Models\Activity;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Load activities from external service
     *
     * If `key` specified - seek activity by key,
     * else - get random record
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function loadActivities(): Response|JsonResponse
    {
        $keyId = request()->get('key', null);

        $activity = ActivitiesService::loadActivity($keyId);
        if ($activity === null) {
            return response()->noContent(404);
        }

        return response()->json([
            'key' => $activity->key
        ]);
    }

    /**
     * Get list of activities from database
     *
     * @return JsonResponse|Response
     */
    public function getActivities(): Response|JsonResponse
    {
        $activitiesRequest = new ActivitiesRequest();
        $activitiesRequest->populateFromArray(request()->all());
        $activities = ActivitiesRepository::getStoredActivitiesList($activitiesRequest);

        return response()->json($activities);
    }

    /**
     * Get one activity, using specified key
     *
     * @param int $key
     * @return Response|JsonResponse
     */
    public function getActivity(int $key): Response|JsonResponse
    {
        $activity = ActivitiesRepository::getStoredActivity($key);
        if ($activity === null) {
            return response()->noContent(404);
        }

        return response()->json($activity);
    }

    /**
     * Delete activity from database
     *
     * @param int $key
     * @return Response|JsonResponse
     */
    public function deleteActivity(int $key): Response|JsonResponse
    {
        $activity = ActivitiesRepository::getStoredActivity($key);
        if ($activity === null) {
            return response()->json(['message' => 'Key not found'], 404);
        }

        $activity->delete();

        return response()->json(['key' => $activity->getAttribute('key')]);
    }
}
