<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        protected $dateFrom = null,
        protected $dateTo = null,
        protected $eventId = null
    ) {}

    public function query()
    {
        return Attendance::with('beneficiary', 'event')
            ->when($this->eventId, fn($q) => $q->where('event_id', $this->eventId))
            ->when($this->dateFrom, fn($q) => $q->whereHas('event', fn($q) => $q->whereDate('event_date', '>=', $this->dateFrom)))
            ->when($this->dateTo,   fn($q) => $q->whereHas('event', fn($q) => $q->whereDate('event_date', '<=', $this->dateTo)));
    }

    public function headings(): array
    {
        return ['ID', 'Beneficiary', 'Event', 'Status', 'Time In', 'Time Out'];
    }

    public function map($row): array
    {
        return [
            $row->attendance_id,
            $row->beneficiary->first_name . ' ' . $row->beneficiary->last_name,
            $row->event->event_name,
            $row->attendance_status,
            $row->time_in,
            $row->time_out,
        ];
    }
}