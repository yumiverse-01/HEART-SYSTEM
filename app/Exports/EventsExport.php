<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EventsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(protected $dateFrom = null, protected $dateTo = null) {}

    public function query()
    {
        return Event::query()
            ->when($this->dateFrom, fn($q) => $q->whereDate('event_date', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('event_date', '<=', $this->dateTo));
    }

    public function headings(): array
    {
        return ['ID', 'Event Name', 'Type', 'Date', 'Location', 'Status', 'Description'];
    }

    public function map($row): array
    {
        return [
            $row->event_id,
            $row->event_name,
            $row->event_type,
            $row->event_date,
            $row->location,
            $row->status,
            $row->description,
        ];
    }
}