<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final class UserExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection(): Collection
    {
        return User::with('division')
            ->get()
            ->map(fn (User $user) => [
                'unique_id' => $user->unique_id,
                'name' => $user->name,
                'email' => $user->email,
                'join_date' => $user->join_date?->format('Y-m-d'),
                'division_code' => $user->division?->kode,
                'division_name' => $user->division?->name,
                'phone' => $user->phone,
                'address' => $user->address,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            ]);
    }

    public function headings(): array
    {
        return [
            'UNIQ ID',
            'Nama',
            'Email',
            'Join Date',
            'Kode Divisi',
            'Divisi',
            'Telefon',
            'Alamat',
            'Tanggal Dibuat',
        ];
    }

    public function map($item): array
    {
        return [
            $item['unique_id'],
            $item['name'],
            $item['email'],
            $item['join_date'],
            $item['division_code'],
            $item['division_name'],
            $item['phone'],
            $item['address'],
            $item['created_at'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}