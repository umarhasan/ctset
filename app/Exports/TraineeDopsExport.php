<?php

namespace App\Exports;

use App\Models\DopsAttempt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TraineeDopsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return DopsAttempt::with(['trainee','rotation','dops'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Trainee',
            'Rotation',
            'DOPS',
            'Date',
            'From Time',
            'To Time',
            'Diagnosis',
            'Procedure',
            'Comments',
            'Status',
            'Created At',
        ];
    }

    public function map($attempt): array
    {
        // Convert JSON fields to readable string
        $diagnosis = $attempt->diagnosis 
            ? collect(json_decode($attempt->diagnosis))
                ->map(fn($d) => $d->name.': '.$d->value)
                ->implode(' | ')
            : '-';

        $procedure = $attempt->procedure 
            ? collect(json_decode($attempt->procedure))
                ->map(fn($p) => $p->name.': '.$p->value)
                ->implode(' | ')
            : '-';

        return [
            $attempt->id,
            $attempt->trainee->name ?? '-',
            $attempt->rotation->title ?? '-',
            $attempt->dops->title ?? '-',
            $attempt->date,
            $attempt->from_time,
            $attempt->to_time ?? '-',
            $diagnosis,
            $procedure,
            $attempt->comments ?? '-',
            $attempt->status,
            $attempt->created_at->format('Y-m-d H:i'),
        ];
    }
}
