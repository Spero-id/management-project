<?php

namespace App\Exports;

use App\Models\ProjectWeeklyMeeting;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final class WeeklyMeetingExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private readonly int $projectId,
    ) {}

    public function collection(): Collection
    {
        return ProjectWeeklyMeeting::where('project_id', $this->projectId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Task',
            'Person in Charge',
            'Start Date',
            'Complete Date',
            'Target Complete Date',
            'Progress (%)',
            'Notes',
        ];
    }

    public function map($meeting): array
    {
        return [
            $meeting->id,
            $meeting->task,
            $meeting->petugas,
            $meeting->start_date,
            $meeting->end_date,
            $meeting->target_date,
            $meeting->progress,
            $meeting->notes ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
