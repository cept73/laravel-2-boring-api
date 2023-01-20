<?php

namespace App\Http\Controllers;

use App\Components\Activities\ActivitiesRepository;
use App\Components\Activities\ActivitiesRequest;
use App\Components\Activities\ActivitiesService;
use App\Components\RemoteStorage\BoredApiRemoteStorage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class ApiController extends Controller
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

        $activity = ActivitiesService::loadActivity(new BoredApiRemoteStorage(), $keyId);
        if ($activity === null) {
            return response()->noContent(self::STATUS_NOT_FOUND);
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
        try {
            $activitiesRequest = new ActivitiesRequest();
            $activitiesRequest->populateFromArray(Request::all());
        } catch (Throwable) {
            return response()->json(['message' => self::MESSAGE_WRONG_REQUEST], self::STATUS_WRONG_INPUT);
        }

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
            return response()->noContent(self::STATUS_NOT_FOUND);
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
            return response()->json(['message' => self::MESSAGE_KEY_NOT_FOUND], self::STATUS_NOT_FOUND);
        }

        $activity->delete();

        return response()->json(['key' => $activity->key]);
    }
}
