<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Beneficiary;
use App\Models\Event;

class AttendanceTest extends HeartSystemTestCase
{
    public function test_worker_can_mark_attendance(): void
    {
        $event = Event::create([
            'event_name' => 'Attendance Event',
            'event_date' => '2026-08-12',
            'location'   => 'Hall A',
            'event_type' => 'Checkup',
            'description'=> null,
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $beneficiary = Beneficiary::create([
            'first_name' => 'Mark',
            'last_name'  => 'User',
            'email'      => 'mark.user@example.com',
            'sex'        => 'Male',
        ]);

        $this->actingAs($this->worker);

        $response = $this->post(route('attendance.mark'), [
            'beneficiary_id'    => $beneficiary->beneficiary_id,
            'event_id'          => $event->event_id,
            'attendance_status' => 'Present',
            'time_in'           => '08:30',
            'time_out'          => '10:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendances', ['beneficiary_id' => $beneficiary->beneficiary_id, 'attendance_status' => 'Present']);
    }

    public function test_worker_can_update_attendance(): void
    {
        $event = Event::create([
            'event_name' => 'Attendance Update',
            'event_date' => '2026-09-01',
            'location'   => 'Hall B',
            'event_type' => 'Screening',
            'description'=> null,
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $beneficiary = Beneficiary::create([
            'first_name' => 'Update',
            'last_name'  => 'User',
            'email'      => 'update.user@example.com',
            'sex'        => 'Female',
        ]);

        $attendance = Attendance::create([
            'beneficiary_id'    => $beneficiary->beneficiary_id,
            'event_id'          => $event->event_id,
            'attendance_status' => 'Absent',
            'time_in'           => null,
            'time_out'          => null,
            'recorded_by'       => $this->worker->user_id,
        ]);

        $this->actingAs($this->worker);

        $response = $this->post(route('attendance.mark'), [
            'beneficiary_id'    => $beneficiary->beneficiary_id,
            'event_id'          => $event->event_id,
            'attendance_status' => 'Present',
            'time_in'           => '09:00',
            'time_out'          => '12:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('attendances', ['attendance_id' => $attendance->attendance_id, 'attendance_status' => 'Present']);
        $this->assertSame(1, Attendance::where('beneficiary_id', $beneficiary->beneficiary_id)->where('event_id', $event->event_id)->count());
    }

    public function test_worker_can_delete_attendance(): void
    {
        $event = Event::create([
            'event_name' => 'Attendance Delete',
            'event_date' => '2026-09-10',
            'location'   => 'Hall C',
            'event_type' => 'Consult',
            'description'=> null,
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $beneficiary = Beneficiary::create([
            'first_name' => 'Delete',
            'last_name'  => 'User',
            'email'      => 'delete.user@example.com',
            'sex'        => 'Male',
        ]);

        $attendance = Attendance::create([
            'beneficiary_id'    => $beneficiary->beneficiary_id,
            'event_id'          => $event->event_id,
            'attendance_status' => 'Present',
            'time_in'           => '09:15',
            'time_out'          => '11:00',
            'recorded_by'       => $this->worker->user_id,
        ]);

        $this->actingAs($this->worker);

        $response = $this->delete(route('attendance.destroy', $attendance->attendance_id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('attendances', ['attendance_id' => $attendance->attendance_id]);
    }

    public function test_duplicate_attendance_prevention(): void
    {
        $event = Event::create([
            'event_name' => 'Attendance Duplicate',
            'event_date' => '2026-10-01',
            'location'   => 'Hall D',
            'event_type' => 'Checkup',
            'description'=> null,
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $beneficiary = Beneficiary::create([
            'first_name' => 'Duplicate',
            'last_name'  => 'User',
            'email'      => 'duplicate.user@example.com',
            'sex'        => 'Other',
        ]);

        Attendance::create([
            'beneficiary_id'    => $beneficiary->beneficiary_id,
            'event_id'          => $event->event_id,
            'attendance_status' => 'Present',
            'recorded_by'       => $this->worker->user_id,
        ]);

        $this->actingAs($this->worker);

        $response = $this->from(route('attendance.create'))
            ->post(route('attendance.store'), [
                'beneficiary_id'    => $beneficiary->beneficiary_id,
                'event_id'          => $event->event_id,
                'attendance_status' => 'Absent',
            ]);

        $response->assertRedirect(route('attendance.create'));
        $response->assertSessionHasErrors(['duplicate']);
    }

    public function test_attendance_index_filters_by_event(): void
    {
        $event = Event::create([
            'event_name' => 'Filter Event',
            'event_date' => '2026-10-05',
            'location'   => 'Hall E',
            'event_type' => 'Screening',
            'description'=> null,
            'status'     => 'Upcoming',
            'created_by' => $this->worker->user_id,
        ]);

        $beneficiary = Beneficiary::create([
            'first_name' => 'Filter',
            'last_name'  => 'User',
            'email'      => 'filter.user@example.com',
            'sex'        => 'Male',
        ]);

        Attendance::create([
            'beneficiary_id'    => $beneficiary->beneficiary_id,
            'event_id'          => $event->event_id,
            'attendance_status' => 'Absent',
            'recorded_by'       => $this->worker->user_id,
        ]);

        $this->actingAs($this->worker);

        $response = $this->get(route('attendance.index', ['event_id' => $event->event_id]));

        $response->assertOk();
        $response->assertSeeText('Filter User');
        $response->assertSeeText('Absent');
    }
}
