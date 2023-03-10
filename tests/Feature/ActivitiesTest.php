<?php

namespace Tests\Feature;

use App\Helpers\UrlHelper;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class ActivitiesTest extends TestCase
{
    public const KEY_SOME_ACTIVITY      = 6826029;

    public const URL_ACTIVITIES         = '/api/activities';
    public const URL_SOME_ACTIVITY      = '/api/activities?key=' . self::KEY_SOME_ACTIVITY;
    public const URL_SOME_ACTIVITY_PATH = '/api/activities/' . self::KEY_SOME_ACTIVITY;

    /**
     * Load random
     *
     * @return void
     */
    public function test_load_random_activity(): void
    {
        $response = $this->post(self::URL_ACTIVITIES);
        $response->assertOk();
    }

    public function test_load_some_activity(): void
    {
        $response = $this->post(self::URL_SOME_ACTIVITY);
        $response->assertOk();
        $response->assertJson(['sent' => true]);
    }

    public function test_get_activities_incorrect_query()
    {
        $someIncorrectData = 'incorrect_data';

        $response = $this->get(UrlHelper::getUrlWithParams(self::URL_ACTIVITIES, [
            'participant'   => $someIncorrectData,
            'price'         => $someIncorrectData,
            'type'          => $someIncorrectData
        ]));

        $response->assertStatus(HttpResponse::HTTP_BAD_REQUEST);
    }

    public function test_get_activities_correct_queries()
    {
        $correctCases = [
            [],
            ['participants' => 1],
            ['price'        => 0.5],
            ['type'         => 'relaxation']
        ];

        $correctStructure = [
            'current_page',
            'data',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ];

        foreach ($correctCases as $correctGetParams) {
            $this->get(UrlHelper::getUrlWithParams(self::URL_ACTIVITIES, $correctGetParams))
                ->assertJsonStructure($correctStructure);
        }
    }

    public function test_delete_some_activity()
    {
        $this->post(self::URL_SOME_ACTIVITY);

        sleep(2);

        $responseSuccessfulDelete = $this->delete(self::URL_SOME_ACTIVITY_PATH);
        $responseSuccessfulDelete->assertOk();
        $responseSuccessfulDelete->assertJson(['key' => self::KEY_SOME_ACTIVITY]);

        $responseFailedDelete = $this->delete(self::URL_SOME_ACTIVITY_PATH);
        $responseFailedDelete->assertNotFound();
        $responseFailedDelete->assertJsonStructure(['message']);

        $this->post(self::URL_SOME_ACTIVITY);
    }
}
