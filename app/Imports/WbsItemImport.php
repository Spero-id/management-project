<?php

namespace App\Imports;

use App\Models\ProjectWBSItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

final class WbsItemImport implements ToCollection, WithHeadingRow, WithStartRow
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
        $parentStack = [];

        foreach ($collection as $row) {

            $level = $row['level'];
            $name = $row['name'];
            $type = $row['type'];
            $from = $row['from'];
            $to = $row['to'];
            $quantity = $row['quantity'];

            if (empty($name)) {
                continue;
            }

            $hierarchyLevel = $this->getHierarchyLevel($level);

            if ($this->isCategory($level)) {
                $item = $this->upsertWbsItem([
                    'project_id' => $this->projectId,
                    'level' => trim($level),
                    'title' => $this->normalizeName($name),
                    'item_type' => 'category',
                    'parent_id' => $this->getParentId($hierarchyLevel, $parentStack),
                    'type' => $type,
                    'from' => $from,
                    'to' => $to,
                ]);

                $this->updateParentStack($hierarchyLevel, $item, $parentStack);
            } else {
                $this->upsertWbsItem([
                    'project_id' => $this->projectId,
                    'level' => trim($level),
                    'title' => $this->normalizeName($name),
                    'item_type' => 'task',
                    'parent_id' => $this->getParentId($hierarchyLevel, $parentStack),
                    'note' => $this->buildNote($quantity),
                    'type' => $type,
                    'from' => $from,
                    'to' => $to,
                    'quantity' => $quantity,
                ]);
            }
        }
    }

    /**
     * Extract hierarchy level from level string (e.g., "1" -> 1, "1.1" -> 2, "1.1.1" -> 3)
     */
    private function getHierarchyLevel(?string $level): int
    {

        if (empty($level)) {
            return 1;
        }

        return substr_count(trim($level), '.') + 1;
    }

    /**
     * Check if level is a category (no decimal point - only whole number like "1")
     */
    private function isCategory(?string $level): bool
    {

        if (empty($level)) {
            return false;
        }

        return preg_match('/^\d+$/', trim($level)) ? true : false;
    }

    /**
     * Normalize name by trimming whitespace
     */
    private function normalizeName(string $name): string
    {
        return trim($name);
    }

    /**
     * Build note from quantity
     */
    private function buildNote(int|string|null $quantity): ?string
    {
        if (empty($quantity)) {
            return null;
        }

        $qty = (int) $quantity;

        return $qty > 0 ? "Kuantitas: {$qty}" : null;
    }

    /**
     * Get parent ID based on hierarchy level
     */
    private function getParentId(int $level, array $parentStack): ?int
    {
        if ($level === 1) {
            return null;
        }

        return $parentStack[$level - 2]?->id;
    }

    /**
     * Update parent stack to track category hierarchy
     */
    private function updateParentStack(int $level, ProjectWBSItem $item, array &$parentStack): void
    {
        $parentStack[$level - 1] = $item;

        // Remove deeper levels when moving to a shallower level
        if (isset($parentStack[$level])) {
            unset($parentStack[$level]);
        }
    }

    /**
     * Update existing WBS item or create new one based on project_id and level
     */
    private function upsertWbsItem(array $data): ProjectWBSItem
    {
        $existing = ProjectWBSItem::where('project_id', $data['project_id'])
            ->where('level', $data['level'])
            ->first();

        if ($existing) {
            $existing->update($data);
            return $existing;
        }

        return ProjectWBSItem::create($data);
    }
}
