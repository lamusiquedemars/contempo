<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_routes_are_not_available_for_contempo(): void
    {
        $this->get('/evenements')->assertNotFound();
        $this->get('/evenements/atelier-public')->assertNotFound();
    }
}
