<?php

namespace Tests\Feature;

use App\Models\Event;

class EventTest extends HeartSystemTestCase
{
    public function test_worker_can_create_event(): void
    {
        $this->actingAs($this->worker);

        $response = $this->post(route('events.store'), [
            'event_name' => 'Health Day',
            'event_date' => '2026-06-15',
            'location'   => 'Community Center',
            'event_type' => 'Outreach',
            'description'=> null,
            'status'     => 'Upcoming',
        ]);

        $response->assertRedirect(route('events.index'));
        $this->assertDatabaseHas('events', ['event_name' => 'Health Day', 'location' => 'Community Center']);
    }

    public function test_worker_can_update_event(): void
    {
        $event = Event::create([
            'event_name' => 'Care Clinic',
            'event_date' => '2026-07-10',
            'location'   => 'Big Hall',
            'event_type' => 'Clinic',
            'description'=> 'Initial meeting',
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $this->actingAs($this->worker);

        $response = $this->put(route('events.update', $event->event_id), [
            'event_name' => 'Care Clinic',
            'event_date' => '2026-07-10',
            'location'   => 'Main Hall',
            'event_type' => 'Clinic',
            'description'=> 'Updated description',
            'status'     => 'Upcoming',
        ]);

        $response->assertRedirect(route('events.index'));
        $this->assertDatabaseHas('events', ['location' => 'Main Hall', 'description' => 'Updated description']);
    }

    public function test_worker_can_delete_event(): void
    {
        $event = Event::create([
            'event_name' => 'Delete Event',
            'event_date' => '2026-08-01',
            'location'   => 'Town Square',
            'event_type' => 'Campaign',
            'description'=> 'Remove this event',
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $this->actingAs($this->worker);

        $response = $this->delete(route('events.destroy', $event->event_id));

        $response->assertRedirect(route('events.index'));
        $this->assertDatabaseMissing('events', ['event_name' => 'Delete Event']);
    }

    public function test_duplicate_event_name_date_is_rejected(): void
    {
        Event::create([
            'event_name' => 'Health Talk',
            'event_date' => '2026-09-01',
            'location'   => 'Center A',
            'event_type' => 'Talk',
            'description'=> null,
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $this->actingAs($this->worker);

        $response = $this->from(route('events.create'))
            ->post(route('events.store'), [
                'event_name' => 'Health Talk',
                'event_date' => '2026-09-01',
                'location'   => 'Center B',
                'event_type' => 'Talk',
                'description'=> 'Duplicate test',
                'status'     => 'Upcoming',
            ]);

        $response->assertRedirect(route('events.create'));
        $response->assertSessionHasErrors(['duplicate']);
    }

    public function test_event_search_by_name_returns_results(): void
    {
        Event::create([
            'event_name' => 'Searchable Event',
            'event_date' => '2026-10-20',
            'location'   => 'Search Hall',
            'event_type' => 'Screening',
            'description'=> 'Search test',
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        Event::create([
            'event_name' => 'Unrelated Event',
            'event_date' => '2026-11-02',
            'location'   => 'Other Hall',
            'event_type' => 'Training',
            'description'=> 'Other event',
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $this->actingAs($this->worker);

        $response = $this->get(route('events.index', ['search' => 'Searchable']));

        $response->assertOk();
        $response->assertSeeText('Searchable Event');
        $response->assertDontSeeText('Unrelated Event');
    }
}
