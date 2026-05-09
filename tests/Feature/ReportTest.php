<?php

namespace Tests\Feature;

use App\Exports\BeneficiariesExport;
use App\Exports\EventsExport;
use App\Models\Attendance;
use App\Models\Beneficiary;
use App\Models\Event;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportTest extends HeartSystemTestCase
{
    public function test_admin_can_view_reports_dashboard(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('reports.index'));

        $response->assertOk();
        $response->assertSeeText('Report Selection');
        $response->assertSeeText('Generate and view reports for evaluation and decision-making');
    }

    public function test_admin_can_view_beneficiaries_report_with_date_range(): void
    {
        Beneficiary::create([
            'first_name'     => 'Jane',
            'middle_name'    => 'A',
            'last_name'      => 'Doe',
            'email'          => 'jane@example.com',
            'birth_date'     => '1995-07-20',
            'age'            => 30,
            'sex'            => 'Female',
            'address'        => '123 Test Ave',
            'contact_number' => '09171234567',
            'guardian_name'  => 'John Doe',
            'date_registered'=> now()->subDays(2)->toDateString(),
            'registered_by'  => $this->admin->user_id,
        ]);

        $this->actingAs($this->admin);

        $response = $this->get(route('reports.beneficiaries', [
            'date_from' => now()->subDays(5)->toDateString(),
            'date_to'   => now()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertSeeText('Beneficiary Registration Report');
        $response->assertSeeText('Jane');
        $response->assertSeeText('Doe');
    }

    public function test_admin_can_export_beneficiaries_report_csv(): void
    {
        Excel::fake();

        $this->actingAs($this->admin);

        $response = $this->get(route('reports.beneficiaries.export', [
            'date_from' => now()->subDays(5)->toDateString(),
            'date_to'   => now()->toDateString(),
        ]));

        $response->assertStatus(200);

        Excel::assertDownloaded('beneficiaries-report.csv', function ($export) {
            return $export instanceof BeneficiariesExport;
        });
    }

    public function test_admin_can_export_events_report_pdf(): void
    {
        Event::create([
            'event_name' => 'Health Seminar',
            'event_type' => 'Outreach',
            'event_date' => now()->toDateString(),
            'location'   => 'Community Center',
            'description'=> 'Health education event',
            'created_by' => $this->admin->user_id,
            'status'     => 'Upcoming',
        ]);

        $pdfMock = \Mockery::mock(\Barryvdh\DomPDF\PDF::class);
        $pdfMock->shouldReceive('setPaper')->with('a4', 'landscape')->andReturnSelf();
        $pdfMock->shouldReceive('download')->with('events-report.pdf')->andReturn(
            response('', 200, ['Content-Disposition' => 'attachment; filename=events-report.pdf'])
        );

        Pdf::shouldReceive('loadView')
            ->once()
            ->with('reports.pdf.events', \Mockery::on(fn ($data) => isset($data['events']) && count($data['events']) === 1))
            ->andReturn($pdfMock);

        $this->actingAs($this->admin);

        $response = $this->get(route('reports.events.export.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename=events-report.pdf');
    }

    public function test_reports_routes_require_authentication(): void
    {
        $response = $this->get(route('reports.index'));
        $response->assertRedirect('/login');

        $response = $this->get(route('reports.beneficiaries'));
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_attendance_report_by_event(): void
    {
        $event = Event::create([
            'event_name' => 'Maternal Care',
            'event_type' => 'Outreach',
            'event_date' => now()->toDateString(),
            'location'   => 'Health Center',
            'description'=> 'Maternal care program',
            'created_by' => $this->admin->user_id,
            'status'     => 'Upcoming',
        ]);

        $beneficiary = Beneficiary::create([
            'first_name'     => 'Anne',
            'middle_name'    => 'B',
            'last_name'      => 'Smith',
            'email'          => 'anne@example.com',
            'birth_date'     => '1990-05-15',
            'age'            => 35,
            'sex'            => 'Female',
            'address'        => '456 Sample St',
            'contact_number' => '09181234567',
            'guardian_name'  => 'Sara Smith',
            'date_registered'=> now()->subDays(4)->toDateString(),

            'registered_by'  => $this->admin->user_id,
        ]);

        Attendance::create([
            'event_id'       => $event->event_id,
            'beneficiary_id' => $beneficiary->beneficiary_id,
            'attendance_status' => 'Present',
            'time_in'        => now()->format('H:i:s'),
            'time_out'       => now()->addHour()->format('H:i:s'),
            'recorded_by'    => $this->admin->user_id,
        ]);

        $this->actingAs($this->admin);

        $response = $this->get(route('reports.attendance', ['event_id' => $event->event_id]));

        $response->assertOk();
        $response->assertSeeText('Event Attendance Report');
        $response->assertSeeText('Maternal Care');
    }
}
