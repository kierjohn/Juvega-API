<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_can_create_new_notification()
    {
        $response = $this->get(route('notifications.addadd'));

        $response->assertStatus(200);
    }
}
