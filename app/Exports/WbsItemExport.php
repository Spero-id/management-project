<?php

namespace App\Exports;

use App\Models\ProjectWBSItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final class WbsItemExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private Collection $flattenedItems;

    public function __construct(
        private readonly int $projectId,
    ) {
        $this->flattenedItems = collect();
        $this->buildFlattenedItems();
    }

    public function collection(): Collection
    {
        return $this->flattenedItems;
    }

    public function headings(): array
    {
        return [
            'Level',
            'Name',
            'Type',
            'From',
            'To',
            'Quantity',
        ];
    }

    public function map($item): array
    {
        return [
            $item['level'],
            $item['title'],
            $item['type'] ?? '',
            $item['from'] ?? '',
            $item['to'] ?? '',
            $item['quantity'] ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Build flattened items from hierarchical structure
     */
    private function buildFlattenedItems(): void
    {
        $rootItems = ProjectWBSItem::where('project_id', $this->projectId)
            ->whereNull('parent_id')
            ->orderBy('id')
            ->get();

        $categoryCounter = 1;
        foreach ($rootItems as $item) {
            $this->processItem($item, (string) $categoryCounter);
            $categoryCounter++;
        }
    }

    /**
     * Process item and its children recursively
     */
    private function processItem(ProjectWBSItem $item, string $level): void
    {
        // Add current item
        $this->flattenedItems->push([
            'level' => $item->item_type === 'category' ? explode('.', $level)[0] : $level,
            'title' => $item->title,
            'type' => $item->type,
            'from' => $item->from,
            'to' => $item->to,
            'quantity' => $this->extractQuantity($item->note),
        ]);

        // Process children
        $children = ProjectWBSItem::where('parent_id', $item->id)
            ->orderBy('id')
            ->get();

        if ($children->isNotEmpty()) {
            $childCounter = 1;
            foreach ($children as $child) {
                $childLevel = "{$level}.{$childCounter}";
                $this->processItem($child, $childLevel);
                $childCounter++;
            }
        }
    }

    /**
     * Extract quantity from note field (e.g., "Kuantitas: 10" -> 10)
     */
    private function extractQuantity(?string $note): ?int
    {
        if (empty($note)) {
            return null;
        }

        if (preg_match('/Kuantitas:\s*(\d+)/', $note, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }
}
