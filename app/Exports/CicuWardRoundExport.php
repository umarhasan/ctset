<?php

namespace App\Exports;

use App\Models\CicuWardRound;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CicuWardRoundExport implements FromCollection, WithHeadings
{
    /**
     * Return collection of ward rounds
     */
    public function collection()
    {
        return CicuWardRound::with(['user','hospital'])
            ->latest()
            ->get()
            ->map(function ($round) {
                return [
                    'ID'           => $round->id,
                    'User'         => $round->user->name ?? '-',
                    'Date'         => $round->date->format('d-m-Y'),
                    'From Time'    => $round->from_time,
                    'To Time'      => $round->to_time ?? '-',
                    'Hospital'     => $round->hospital->name ?? '-',
                    'Involvement'  => $round->involvement,

                ];
            });
    }

    /**
     * Column headings for Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Date',
            'From Time',
            'To Time',
            'Hospital',
            'Involvement',
        ];
    }
}
