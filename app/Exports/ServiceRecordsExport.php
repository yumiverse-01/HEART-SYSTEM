<?php

namespace App\Exports;

use App\Models\EventServiceRecord;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ServiceRecordsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        protected $dateFrom = null,
        protected $dateTo = null,
        protected $eventId = null,
        protected $serviceType = null
    ) {}

    public function query()
    {
        return EventServiceRecord::with('beneficiary', 'event', 'providedBy')
            ->when($this->dateFrom,    fn($q) => $q->whereDate('service_date', '>=', $this->dateFrom))
            ->when($this->dateTo,      fn($q) => $q->whereDate('service_date', '<=', $this->dateTo))
            ->when($this->eventId,     fn($q) => $q->where('event_id', $this->eventId))
            ->when($this->serviceType, fn($q) => $q->where('service_type', 'like', '%' . $this->serviceType . '%'));
    }

    public function headings(): array
    {
        return ['ID', 'Beneficiary', 'Event', 'Service Type', 'Diagnosis', 'Treatment', 'Remarks', 'Provided By', 'Service Date'];
    }

    public function map($row): array
    {
        return [
            $row->service_id,
            $row->beneficiary->first_name . ' ' . $row->beneficiary->last_name,
            $row->event->event_name,
            $row->service_type,
            $row->diagnosis,
            $row->treatment_given,
            $row->remarks,
            $row->providedBy ? $row->providedBy->first_name . ' ' . $row->providedBy->last_name : 'Unknown',
            $row->service_date,
        ];
    }
}