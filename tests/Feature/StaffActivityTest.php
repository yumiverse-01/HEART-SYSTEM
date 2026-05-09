<?php

namespace Tests\Feature;

use App\Models\StaffActivityLog;

class StaffActivityTest extends HeartSystemTestCase
{
    public function test_admin_can_view_staff_activity_index(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('staff-activities.index'));

        $response->assertOk();
        $response->assertSeeText('Staff Activity Logs');
    }

    public function test_staff_activity_search_by_activity_name(): void
    {
        StaffActivityLog::create([
            'user_id'          => $this->admin->user_id,
            'activity_name'    => 'Viewed Beneficiary List',
            'module'           => 'Beneficiary',
            'description'      => 'Staff viewed the beneficiary list',
            'timestamp'        => now(),
            'activity_details' => 'Search test',
        ]);

        $this->actingAs($this->admin);

        $response = $this->get(route('staff-activities.index', ['search' => 'Viewed Beneficiary List']));

        $response->assertOk();
        $response->assertSeeText('Viewed Beneficiary List');
    }

    public function test_staff_activity_filter_by_module(): void
    {
        StaffActivityLog::create([
            'user_id'          => $this->admin->user_id,
            'activity_name'    => 'Viewed Event List',
            'module'           => 'Outreach Event',
            'description'      => 'Staff viewed the event list',
            'timestamp'        => now(),
            'activity_details' => 'Filter test',
        ]);

        StaffActivityLog::create([
            'user_id'          => $this->admin->user_id,
            'activity_name'    => 'Viewed Beneficiary List',
            'module'           => 'Beneficiary',
            'description'      => 'Staff viewed the beneficiary list',
            'timestamp'        => now(),
            'activity_details' => 'Filter test',
        ]);

        $this->actingAs($this->admin);

        $response = $this->get(route('staff-activities.index', ['type' => 'Outreach Event']));

        $response->assertOk();
        $response->assertSeeText('Viewed Event List');
        $response->assertDontSeeText('Viewed Beneficiary List');
    }

    public function test_staff_activity_index_requires_authentication(): void
    {
        $response = $this->get(route('staff-activities.index'));

        $response->assertRedirect('/login');
    }

    public function test_staff_activity_search_by_description(): void
    {
        StaffActivityLog::create([
            'user_id'          => $this->admin->user_id,
            'activity_name'    => 'Exported Report',
            'module'           => 'Report',
            'description'      => 'Exported beneficiary report',
            'timestamp'        => now(),
            'activity_details' => 'Description search test',
        ]);

        $this->actingAs($this->admin);

        $response = $this->get(route('staff-activities.index', ['search' => 'beneficiary report']));

        $response->assertOk();
        $response->assertSeeText('Exported Report');
    }
}
