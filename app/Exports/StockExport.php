<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final class StockExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection(): Collection
    {
        return Product::with('stock')
            ->get()
            ->map(fn ($product) => [
                'id' => $product->id,
                'brand' => $product->brand,
                'type' => $product->type,
                'stock_quantity' => $product->stock?->stock_quantity ?? 0,
            ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Brand',
            'Type',
            'Stock Quantity',
        ];
    }

    public function map($item): array
    {
        return [
            $item['id'],
            $item['brand'],
            $item['type'],
            $item['stock_quantity'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
