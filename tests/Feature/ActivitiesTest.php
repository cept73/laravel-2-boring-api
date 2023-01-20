<?php

namespace Tests\Feature;

use App\Helpers\UrlHelper;
use App\Http\Controllers\Controller;
use App\Observers\ActivitiesObserver;
use Tests\TestCase;

class ActivitiesTest extends TestCase
{
    public const KEY_SOME_ACTIVITY      = 6826029;

    public const URL_ACTIVITIES         = '/api/activities';
    public const URL_GET_SOME_ACTIVITY  = '/api/activities/' . self::KEY_SOME_ACTIVITY;

    /**
     * Load random
     *
     * @return void
     */
    public function test_load_random_activity(): void
    {
        $response = $this->post(self::URL_ACTIVITIES);
        $responseKey = $response->json('key');

        $response->assertOk();
        $response->assertJsonStructure(['key']);
        $this->assertTrue((int)$responseKey == $responseKey);
    }

    public function test_load_some_activity(): void
    {
        $response = $this->post(self::URL_GET_SOME_ACTIVITY);

        $response->assertOk();
        $response->assertJson(['key' => self::KEY_SOME_ACTIVITY]);
    }

    public function test_get_activities_incorrect_query()
    {
        $response = $this->get(UrlHelper::getUrlWithParams(self::URL_ACTIVITIES, [
            'participant'   => 'incorrect_data',
            'price'         => 'incorrect_data',
            'type'          => 'incorrect_data'
        ]));

        $response->assertStatus(Controller::STATUS_WRONG_INPUT);
    }

    public function test_get_activities_correct_queries()
    {
        $correctCases = [
            [],
            ['participant'  => 1],
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
            'links' => [
                [
                    'url',
                    'label',
                    'active',
                ],
                [
                    'url',
                    'label',
                    'active',
                ],
                [
                    'url',
                    'label',
                    'active',
                ],
            ],
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ];

        foreach ($correctCases as $correctGetParams) {
            $response = $this->get(UrlHelper::getUrlWithParams(self::URL_ACTIVITIES, $correctGetParams));
            $response->assertJsonStructure($correctStructure);
        }
    }

    public function test_delete_some_activity()
    {
        $this->post(self::URL_GET_SOME_ACTIVITY);

        $responseSuccessfulDelete = $this->delete(self::URL_GET_SOME_ACTIVITY);
        $responseSuccessfulDelete->assertOk();
        $responseSuccessfulDelete->assertJson(['key' => self::KEY_SOME_ACTIVITY]);

        $responseFailedDelete = $this->delete(self::URL_GET_SOME_ACTIVITY);
        $responseFailedDelete->assertNotFound();
        $responseFailedDelete->assertJsonStructure(['message']);

        $this->post(self::URL_GET_SOME_ACTIVITY);
    }

    public function test_reaction_after_new_post_loaded()
    {
        tap($this->mock(ActivitiesObserver::class), function ($observer) {
            $observer->expects('created')->andReturnNull();
        });

        $this->post(self::URL_ACTIVITIES);
    }
}
