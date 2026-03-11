<?php

namespace App\Exports;

use App\Models\Beneficiary;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BeneficiariesExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(protected $dateFrom = null, protected $dateTo = null) {}

    public function query()
    {
        return Beneficiary::query()
            ->when($this->dateFrom, fn($q) => $q->whereDate('date_registered', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('date_registered', '<=', $this->dateTo));
    }

    public function headings(): array
    {
        return ['ID', 'First Name', 'Last Name', 'Email', 'Age', 'Sex', 'Address', 'Contact', 'Date Registered'];
    }

    public function map($row): array
    {
        return [
            $row->beneficiary_id,
            $row->first_name,
            $row->last_name,
            $row->email,
            $row->age,
            $row->sex,
            $row->address,
            $row->contact_number,
            $row->date_registered,
        ];
    }
}