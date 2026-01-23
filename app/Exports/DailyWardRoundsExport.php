<?php

namespace App\Exports;

use App\Models\DailyWardRound;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DailyWardRoundsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return DailyWardRound::with(['hospital','rotation','consultant'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'From Time',
            'To Time',
            'Hospital',
            'Rotation',
            'Status',
            'Consultant',
            'Fees',
            'Created At'
        ];
    }

    public function map($round): array
    {
        return [
            $round->id,
            $round->date,
            $round->from_time,
            $round->to_time,
            $round->hospital->name ?? '-',
            $round->rotation->short_name ?? '-',
            $round->involvement,
            $round->consultant->name ?? '-',
            implode(', ', $round->consultant_fees ?? []),
            $round->created_at
        ];
    }
}
