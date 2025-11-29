<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $division = Division::first();

        // New BOD users
        $bodUsers = [
            [
                'unique_id' => 'SIS-0001-0908-BOD',
                'no_karyawan' => 'SIS-0001',
                'no_quotation' => 5,
                'name' => 'Asnan Bagus Setiawan',
                'email' => 'superadmin@example.com',
                'join_month' => 'January',
                'join_year' => '2025',
                'division_id' => $division->id,
                'password' => Hash::make('12345678'),
                'foto' => null,
            ],
            [
                'unique_id' => 'SIS-0002-0908-BOD',
                'no_karyawan' => 'SIS-0002',
                'no_quotation' => 5,
                'name' => 'Peter Tejakusuma',
                'email' => 'peter.tejakusuma@example.com',
                'join_month' => 'September',
                'join_year' => '2008',
                'division_id' => $division->id,
                'password' => Hash::make('12345678'),
                'foto' => null,
            ],
            [
                'unique_id' => 'SIS-0003-0908-BOD',
                'no_karyawan' => 'SIS-0003',
                'no_quotation' => 7,
                'name' => 'Arry Darmawan',
                'email' => 'arry.darmawan@example.com',
                'join_month' => 'September',
                'join_year' => '2008',
                'division_id' => $division->id,
                'password' => Hash::make('12345678'),
                'foto' => null,
            ],
            [
                'unique_id' => 'SIS-0004-0908-BOD',
                'no_quotation' => 8,
                'no_karyawan' => 'SIS-0004',
                'name' => 'Rahmat Triyadi',
                'email' => 'rahmat.triyadi@example.com',
                'join_month' => 'September',
                'join_year' => '2008',
                'division_id' => $division->id,
                'password' => Hash::make('12345678'),
                'foto' => null,
            ],
        ];

        foreach ($bodUsers as $bodData) {
            $bod = User::create($bodData);
            $bod->assignRole('BOD');
        }

        // Create 5 Sales Users
        $salesData = [
            [
                'unique_id' => 'SIS-0017-0923-SMT',
                'no_karyawan' => 'SIS-0017',
                'name' => 'Siti Hasnah Nadia',
                'email' => 'sales1@example.com',
                'join_month' => 'September',
                'join_year' => '2023',
                'no_quotation' => 9,
            ],
            [
                'unique_id' => 'SIS-0018-0124-SMT',
                'no_karyawan' => 'SIS-0018',
                'name' => 'Ahmad Rizki Pratama',
                'email' => 'sales2@example.com',
                'join_month' => 'January',
                'join_year' => '2024',
                'no_quotation' => 10,
            ],
            [
                'unique_id' => 'SIS-0019-0324-SMT',
                'no_karyawan' => 'SIS-0019',
                'name' => 'Dewi Sartika Putri',
                'email' => 'sales3@example.com',
                'join_month' => 'March',
                'join_year' => '2024',
                'no_quotation' => 11,
            ],
            [
                'unique_id' => 'SIS-0020-0524-SMT',
                'no_karyawan' => 'SIS-0020',
                'name' => 'Budi Santoso',
                'email' => 'sales4@example.com',
                'join_month' => 'May',
                'join_year' => '2024',
                'no_quotation' => 12,
            ],
            [
                'unique_id' => 'SIS-0021-0724-SMT',
                'no_karyawan' => 'SIS-0021',
                'name' => 'Maya Sari Wijaya',
                'email' => 'sales5@example.com',
                'join_month' => 'July',
                'join_year' => '2024',
                'no_quotation' => 13,
            ],
        ];

        foreach ($salesData as $salesInfo) {
            $sales = User::create([
                'unique_id' => $salesInfo['unique_id'],
                'no_karyawan' => $salesInfo['no_karyawan'],
                'name' => $salesInfo['name'],
                'email' => $salesInfo['email'],
                'join_month' => $salesInfo['join_month'],
                'join_year' => $salesInfo['join_year'],
                'division_id' => 2, // SALES & MARKETING division
                'no_quotation' => $salesInfo['no_quotation'],
                'password' => Hash::make('12345678'),
                'foto' => null,
            ]);

            $sales->assignRole('SALES');
        }

        // Create sales targets for each sales user
        $salesUsers = User::whereHas('roles', fn ($query) => $query->where('name', 'SALES'))->get();
        foreach ($salesUsers as $salesUser) {
            \App\Models\SalesTarget::create([
                'user_id' => $salesUser->id,
                'target_gross_profit' => 10000000,
                'target_monthly' => 10000000,
                'target_yearly' => 10000000,
                'year' => 2025,
            ]);
        }

        // Create Project Users
        $projectUsers = [
            [
                'unique_id' => 'SIS-0010-0117-PTK',
                'no_karyawan' => 'SIS-0010',
                'name' => 'Arifin',
                'email' => 'arifin@example.com',
                'join_month' => 'January',
                'join_year' => '2017',
            ],
            [
                'unique_id' => 'SIS-0011-0720-PTK',
                'no_karyawan' => 'SIS-0011',
                'name' => 'Renaldy',
                'email' => 'renaldy@example.com',
                'join_month' => 'July',
                'join_year' => '2020',
            ],
        ];

        $ptkDivision = Division::where('kode', 'PTK')->first();
        foreach ($projectUsers as $puser) {
            $userData = [
                'unique_id' => $puser['unique_id'],
                'no_karyawan' => $puser['no_karyawan'],
                'name' => $puser['name'],
                'email' => $puser['email'],
                'join_month' => $puser['join_month'],
                'join_year' => $puser['join_year'],
                'division_id' => $ptkDivision ? $ptkDivision->id : $division->id,
                'password' => Hash::make('12345678'),
                'foto' => null,
            ];

            $projUser = User::create($userData);
            $projUser->assignRole('PROJECT');
        }

        // Create Finance Users
        $finDivision = Division::where('kode', 'FIN')->first();
        $financeUsers = [
            [
                'unique_id' => 'SIS-0014-0217-FIN',
                'no_karyawan' => 'SIS-0014',
                'name' => 'Bambang Hartono',
                'email' => 'finance1@example.com',
                'join_month' => 'February',
                'join_year' => '2017',
            ],
            [
                'unique_id' => 'SIS-0015-0820-FIN',
                'no_karyawan' => 'SIS-0015',
                'name' => 'Sri Mulyani',
                'email' => 'finance2@example.com',
                'join_month' => 'August',
                'join_year' => '2020',
            ],
        ];

        foreach ($financeUsers as $financeData) {
            $financeUser = User::create([
                'unique_id' => $financeData['unique_id'],
                'no_karyawan' => $financeData['no_karyawan'],
                'name' => $financeData['name'],
                'email' => $financeData['email'],
                'join_month' => $financeData['join_month'],
                'join_year' => $financeData['join_year'],
                'division_id' => $finDivision ? $finDivision->id : $division->id,
                'password' => Hash::make('12345678'),
                'foto' => null,
            ]);
            $financeUser->assignRole('FINANCE');
        }

        // Create Logistic Users
        $gafDivision = Division::where('kode', 'LGS')->first();
        $logisticUsers = [
            [
                'unique_id' => 'SIS-0012-0318-GAF',
                'no_karyawan' => 'SIS-0012',
                'name' => 'Andi Wijaya',
                'email' => 'logistic1@example.com',
                'join_month' => 'March',
                'join_year' => '2018',
            ],
            [
                'unique_id' => 'SIS-0013-0619-GAF',
                'no_karyawan' => 'SIS-0013',
                'name' => 'Siti Nurhaliza',
                'email' => 'logistic2@example.com',
                'join_month' => 'June',
                'join_year' => '2019',
            ],
        ];

        foreach ($logisticUsers as $logisticData) {
            $logisticUser = User::create([
                'unique_id' => $logisticData['unique_id'],
                'no_karyawan' => $logisticData['no_karyawan'],
                'name' => $logisticData['name'],
                'email' => $logisticData['email'],
                'join_month' => $logisticData['join_month'],
                'join_year' => $logisticData['join_year'],
                'division_id' => $gafDivision ? $gafDivision->id : $division->id,
                'password' => Hash::make('12345678'),
                'foto' => null,
            ]);
            $logisticUser->assignRole('LOGISTIC');
        }
    }
}
