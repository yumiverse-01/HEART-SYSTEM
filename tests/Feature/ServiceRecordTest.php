<?php

namespace Tests\Feature;

use App\Models\Beneficiary;
use App\Models\Event;
use App\Models\EventServiceRecord;

class ServiceRecordTest extends HeartSystemTestCase
{
    public function test_worker_can_create_service_record(): void
    {
        $event = Event::create([
            'event_name' => 'Service Event',
            'event_date' => '2026-10-20',
            'location'   => 'Clinic Hall',
            'event_type' => 'Consultation',
            'description'=> null,
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $beneficiary = Beneficiary::create([
            'first_name' => 'Service',
            'last_name'  => 'Patient',
            'email'      => 'service.patient@example.com',
            'sex'        => 'Female',
        ]);

        $this->actingAs($this->worker);

        $response = $this->post(route('service-records.store'), [
            'event_id'        => $event->event_id,
            'beneficiary_id'  => $beneficiary->beneficiary_id,
            'service_type'    => 'Consultation',
            'diagnosis'       => 'Mild symptoms',
            'treatment_given' => 'Rest and hydration',
            'service_date'    => '2026-10-20',
        ]);

        $response->assertRedirect(route('service-records.index'));
        $this->assertDatabaseHas('event_service_records', ['diagnosis' => 'Mild symptoms']);
    }

    public function test_worker_can_update_service_record(): void
    {
        $event = Event::create([
            'event_name' => 'Service Update',
            'event_date' => '2026-11-05',
            'location'   => 'Clinic Hall B',
            'event_type' => 'Consultation',
            'description'=> null,
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $beneficiary = Beneficiary::create([
            'first_name' => 'Update',
            'last_name'  => 'Patient',
            'email'      => 'update.patient@example.com',
            'sex'        => 'Male',
        ]);

        $record = EventServiceRecord::create([
            'event_id'        => $event->event_id,
            'beneficiary_id'  => $beneficiary->beneficiary_id,
            'service_type'    => 'Checkup',
            'diagnosis'       => 'Initial diagnosis',
            'treatment_given' => 'Initial treatment',
            'service_date'    => '2026-11-05',
            'provided_by'     => $this->worker->user_id,
        ]);

        $this->actingAs($this->worker);

        $response = $this->put(route('service-records.update', $record->service_id), [
            'event_id'        => $event->event_id,
            'beneficiary_id'  => $beneficiary->beneficiary_id,
            'service_type'    => 'Checkup',
            'diagnosis'       => 'Updated diagnosis',
            'treatment_given' => 'Updated treatment',
            'service_date'    => '2026-11-05',
        ]);

        $response->assertRedirect(route('service-records.index'));
        $this->assertDatabaseHas('event_service_records', ['diagnosis' => 'Updated diagnosis']);
    }

    public function test_worker_can_delete_service_record(): void
    {
        $event = Event::create([
            'event_name' => 'Service Delete',
            'event_date' => '2026-11-10',
            'location'   => 'Clinic Hall C',
            'event_type' => 'Consultation',
            'description'=> null,
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $beneficiary = Beneficiary::create([
            'first_name' => 'Delete',
            'last_name'  => 'Patient',
            'email'      => 'delete.patient@example.com',
            'sex'        => 'Other',
        ]);

        $record = EventServiceRecord::create([
            'event_id'        => $event->event_id,
            'beneficiary_id'  => $beneficiary->beneficiary_id,
            'service_type'    => 'Treatment',
            'diagnosis'       => 'To be deleted',
            'treatment_given' => 'Temporary care',
            'service_date'    => '2026-11-10',
            'provided_by'     => $this->worker->user_id,
        ]);

        $this->actingAs($this->worker);

        $response = $this->delete(route('service-records.destroy', $record->service_id));

        $response->assertRedirect(route('service-records.index'));
        $this->assertDatabaseMissing('event_service_records', ['service_id' => $record->service_id]);
    }

    public function test_duplicate_service_record_prevention(): void
    {
        $event = Event::create([
            'event_name' => 'Service Duplicate',
            'event_date' => '2026-12-01',
            'location'   => 'Clinic Hall D',
            'event_type' => 'Treatment',
            'description'=> null,
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $beneficiary = Beneficiary::create([
            'first_name' => 'Duplicate',
            'last_name'  => 'Patient',
            'email'      => 'duplicate.patient@example.com',
            'sex'        => 'Female',
        ]);

        EventServiceRecord::create([
            'event_id'        => $event->event_id,
            'beneficiary_id'  => $beneficiary->beneficiary_id,
            'service_type'    => 'Treatment',
            'diagnosis'       => 'Original diagnosis',
            'treatment_given' => 'Original treatment',
            'service_date'    => '2026-12-01',
            'provided_by'     => $this->worker->user_id,
        ]);

        $this->actingAs($this->worker);

        $response = $this->from(route('service-records.create'))
            ->post(route('service-records.store'), [
                'event_id'        => $event->event_id,
                'beneficiary_id'  => $beneficiary->beneficiary_id,
                'service_type'    => 'Treatment',
                'diagnosis'       => 'Duplicate diagnosis',
                'treatment_given' => 'Duplicate treatment',
                'service_date'    => '2026-12-01',
            ]);

        $response->assertRedirect(route('service-records.create'));
        $response->assertSessionHasErrors(['duplicate']);
    }

    public function test_service_records_search_by_beneficiary_name(): void
    {
        $event = Event::create([
            'event_name' => 'Search Service',
            'event_date' => '2026-12-10',
            'location'   => 'Clinic Hall E',
            'event_type' => 'Treatment',
            'description'=> null,
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $beneficiary = Beneficiary::create([
            'first_name' => 'Search',
            'last_name'  => 'Patient',
            'email'      => 'search.patient@example.com',
            'sex'        => 'Male',
        ]);

        EventServiceRecord::create([
            'event_id'        => $event->event_id,
            'beneficiary_id'  => $beneficiary->beneficiary_id,
            'service_type'    => 'Treatment',
            'diagnosis'       => 'Search diagnosis',
            'treatment_given' => 'Search treatment',
            'service_date'    => '2026-12-10',
            'provided_by'     => $this->worker->user_id,
        ]);

        $this->actingAs($this->worker);

        $response = $this->get(route('service-records.index', ['search' => 'Search']));

        $response->assertOk();
        $response->assertSeeText('Search Patient');
    }
}
