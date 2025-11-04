<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuotationItemsImport implements ToArray, WithHeadingRow
{
    /**
     * @return array
     */
    public function array(array $array)
    {
        $items = [];

        foreach ($array as $row) {
            // Skip empty rows
            if (empty($row['product_id']) && empty($row['quantity'])) {
                continue;
            }

            $items[] = [
                'product_id' => (int) ($row['product_id'] ?? 0),
                'quantity' => (int) ($row['quantity'] ?? 0),
                'unit_price' => isset($row['unit_price']) ? (float) $row['unit_price'] : 0,
            ];
        }

        return $items;
    }
}
