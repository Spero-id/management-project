<?php

namespace App\Imports;

use App\Models\ProjectWeeklyMeeting;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

final class WeeklyMeetingImport implements ToCollection, WithHeadingRow, WithStartRow
{
    public function __construct(
        private readonly int $projectId,
    ) {}

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $collection): void
    {
        foreach ($collection as $row) {
            $id = $row['id'] ?? null;
            $task = $row['task'] ?? null;
            $petugas = $row['person_in_charge'] ?? null;
            $startDate = $row['start_date'] ?? null;
            $endDate = $row['complete_date'] ?? null;
            $targetDate = $row['target_complete_date'] ?? null;
            $progress = $row['progress'] ?? 0;
            $notes = $row['notes'] ?? null;

            if (empty($task) || empty($petugas) || empty($startDate) || empty($endDate) || empty($targetDate)) {
                continue;
            }

            $data = [
                'project_id' => $this->projectId,
                'task' => trim($task),
                'petugas' => trim($petugas),
                'start_date' => $this->parseDate($startDate),
                'end_date' => $this->parseDate($endDate),
                'target_date' => $this->parseDate($targetDate),
                'progress' => (int) min(100, max(0, $progress)),
                'notes' => $notes ? trim($notes) : null,
            ];

            // Check if ID exists and record exists in database
            if ($id && ProjectWeeklyMeeting::where('id', $id)->exists()) {
                // Update existing record
                ProjectWeeklyMeeting::where('id', $id)->update($data);
            } else {
                // Create new record (ID will be auto-increment)
                ProjectWeeklyMeeting::create($data);
            }
        }
    }

    /**
     * Parse date from various formats
     */
    private function parseDate(mixed $date): string
    {
        if (is_numeric($date)) {
            // Excel serial date number
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
        }

        // Try to parse as string date
        return date('Y-m-d', strtotime($date));
    }
}
