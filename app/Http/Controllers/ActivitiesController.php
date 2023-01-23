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
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
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
            return Response::json(['message' => self::MESSAGE_WRONG_REQUEST], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get one activity, using specified key
     *
     * @param int $key
     * @return JsonResponse
     */
    public function show(int $key): JsonResponse
    {
        try {
            return Response::json(ActivitiesRepository::getStoredActivityOrFail($key));
        }
        catch (NotFoundResourceException) {
            return $this->itemNotFoundResponse();
        }
        catch (Throwable $exception) {
            return $this->serverError($exception);
        }
    }

    /**
     * Delete activity from database
     *
     * @param int $key
     * @return Response|JsonResponse
     */
    public function destroy(int $key): Response|JsonResponse
    {
        try {
            $activity = ActivitiesRepository::getStoredActivityOrFail($key);
            $activity->delete();

            return Response::json(['key' => $activity->key]);
        }
        catch (NotFoundResourceException) {
            return $this->itemNotFoundResponse();
        }
        catch (Throwable $exception) {
            return $this->serverError($exception);
        }
    }

    private function itemNotFoundResponse(): JsonResponse
    {
        return Response::json(['message' => self::MESSAGE_ITEM_NOT_FOUND], HttpResponse::HTTP_NOT_FOUND);
    }

    private function serverError(Throwable $exception): JsonResponse
    {
        return Response::json(['message' => $exception->getMessage()], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
