<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Product;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RoleSeeder::class);

        $setting = [
            ['setting_name' => 'currency_exchange_rate', 'setting_value' => '15000'],
        ];
        foreach ($setting as $settingData) {
            \App\Models\Setting::create([
                'setting_name' => $settingData['setting_name'],
                'setting_value' => $settingData['setting_value'],
            ]);
        }

        $divisions = [
            ['name' => 'BOD', 'kode' => 'BOD', 'is_generate_sales_quotation_number' => true],
            ['name' => 'SALES & MARKETING', 'kode' => 'SMT', 'is_generate_sales_quotation_number' => true],
            ['name' => 'MANAGER FINANCE', 'kode' => 'FIN', 'is_generate_sales_quotation_number' => false],
            ['name' => 'GENERAL MANAGER', 'kode' => 'GEN', 'is_generate_sales_quotation_number' => false],
            ['name' => 'PROJECT & TECHNICAL', 'kode' => 'PTK', 'is_generate_sales_quotation_number' => false],
            ['name' => 'GENERAL AFFAIRS', 'kode' => 'GAF', 'is_generate_sales_quotation_number' => false],
        ];

        foreach ($divisions as $divisionData) {
            Division::create([
                'name' => $divisionData['name'],
                'kode' => $divisionData['kode'],
                'is_generate_sales_quotation_number' => $divisionData['is_generate_sales_quotation_number'],
            ]);
        }

        $prospectStatus = [
            ['name' => 'Prospecting', 'persentage' => 15, 'color' => '#3B82F6'],
            ['name' => 'Qualification', 'persentage' => 30, 'color' => '#F59E0B'],
            ['name' => 'Proposal / Quotation', 'persentage' => 50, 'color' => '#8B5CF6'],
            ['name' => 'Negotiation', 'persentage' => 75, 'color' => '#06B6D4'],
            ['name' => 'Closing', 'persentage' => 100, 'color' => '#10B981'],
            ['name' => 'LOST', 'persentage' => 0, 'color' => '#EF4444'],
        ];

        foreach ($prospectStatus as $statusData) {
            \App\Models\ProspectStatus::create([
                'name' => $statusData['name'],
                'persentage' => $statusData['persentage'],
                'color' => $statusData['color'],
            ]);
        }

        $instalation = [
            ['name' => 'Installation A', 'description' => 'Installation description A', 'proportional' => 25.00],
            ['name' => 'Installation B', 'description' => 'Installation description B', 'proportional' => 50.00],
            ['name' => 'Installation C', 'description' => 'Installation description C', 'proportional' => 75.00],
        ];

        foreach ($instalation as $installationData) {
            \App\Models\Installation::create([
                'name' => $installationData['name'],
                'description' => $installationData['description'],
                'proportional' => $installationData['proportional'],
            ]);
        }

        $accommodations = [
            ['name' => 'HARGA PENGINAPAN', 'price' => 300000],
            ['name' => 'HARGA TRANSPORTASI KENDARAAN', 'price' => 300000],
        ];

        foreach ($accommodations as $accommodationData) {
            \App\Models\Accommodation::create([
                'name' => $accommodationData['name'],
                'price' => $accommodationData['price'],
            ]);
        }

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
            ],
        ];

        foreach ($bodUsers as $bodData) {
            $bod = User::create($bodData);
            $bod->assignRole('BOD');
        }

        // Create products first before creating quotations
        $products = [
            ['brand' => 'BIAMP', 'type' => 'TesiraFORTE AI', 'description' => 'Fixed I/O DSP with 12 analog inputs, 8 analog outputs, and 8 channels configurable USB audio', 'price_list' => 68145520, 'up' => 50, 'harga_dasar_dolar' => 2004.28, 'harga_dasar_rupiah_fob_luar_negeri' => 34072760, 'harga_dasar_rupiah_fob_jakarta' => 35572760, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5],
            ['brand' => 'BIAMP', 'type' => 'TesiraFORTE AVB AI', 'description' => 'Fixed I/O DSP with 12 analog inputs, 8 analog outputs, 8 channels configurable USB audio, and 128 x 128 channels of AVB', 'price_list' => 77539040, 'up' => 50, 'harga_dasar_dolar' => 2280.56, 'harga_dasar_rupiah_fob_luar_negeri' => 38769520, 'harga_dasar_rupiah_fob_jakarta' => 40269520, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5],
            ['brand' => 'BIAMP', 'type' => 'TesiraFORTE AVB CI', 'description' => 'Fixed I/O DSP with 12 analog inputs, 8 analog outputs, 8 channels configurable USB audio, 128 x 128 channels of AVB, and AEC technology (all 12 inputs)', 'price_list' => 96283240, 'up' => 50, 'harga_dasar_dolar' => 2831.86, 'harga_dasar_rupiah_fob_luar_negeri' => 48141620, 'harga_dasar_rupiah_fob_jakarta' => 49641620, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5],
            ['brand' => 'BIAMP', 'type' => 'TesiraFORTE AVB VT', 'description' => 'Fixed I/O DSP with 12 analog inputs, 8 analog outputs, 8 channels configurable USB audio, 128 x 128 channels of AVB, AEC technology (all 12 inputs), 2 channel VoIP, and standard FXO telephone interface', 'price_list' => 103328380, 'up' => 50, 'harga_dasar_dolar' => 3039.07, 'harga_dasar_rupiah_fob_luar_negeri' => 51664190, 'harga_dasar_rupiah_fob_jakarta' => 53164190, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5],
            ['brand' => 'BIAMP', 'type' => 'TesiraFORTE AVB VT4', 'description' => 'Fixed I/O DSP with 4 analog inputs, 4 analog outputs, 8 channels configurable USB audio, 128 x 128 channels of AVB, AEC technology (all 4 inputs), 2 channel VoIP, and standard FXO telephone interface', 'price_list' => 72842280, 'up' => 50, 'harga_dasar_dolar' => 2142.42, 'harga_dasar_rupiah_fob_luar_negeri' => 36421140, 'harga_dasar_rupiah_fob_jakarta' => 37921140, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5],
            ['brand' => 'CRESTRON', 'type' => 'CP4', 'description' => 'Control System', 'price_list' => 86932560, 'up' => 50, 'harga_dasar_dolar' => 2556.84, 'harga_dasar_rupiah_fob_luar_negeri' => 43466280, 'harga_dasar_rupiah_fob_jakarta' => 44966280, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5],
            ['brand' => 'CRESTRON', 'type' => 'TS1070', 'description' => 'Touchscreen 10 inch', 'price_list' => 84584180, 'up' => 50, 'harga_dasar_dolar' => 2487.77, 'harga_dasar_rupiah_fob_luar_negeri' => 42292090, 'harga_dasar_rupiah_fob_jakarta' => 43792090, 'distributor_origin' => 'SINGAPORE', 'shipping_fee_by_air' => 300000, 'weight' => 5],
            ['brand' => 'VINTECH', 'type' => 'VTC1000', 'description' => 'Digital Conference', 'price_list' => 17000000, 'up' => 50, 'harga_dasar_dolar' => 500.00, 'harga_dasar_rupiah_fob_luar_negeri' => 8500000, 'harga_dasar_rupiah_fob_jakarta' => 10000000, 'distributor_origin' => 'CHINA', 'shipping_fee_by_air' => 300000, 'weight' => 5],
            ['brand' => 'VINTECH', 'type' => 'VLM-MC-TC-154K', 'description' => 'Monitor Lift 17 inch 4K double motor', 'price_list' => 23800000, 'up' => 50, 'harga_dasar_dolar' => 700.00, 'harga_dasar_rupiah_fob_luar_negeri' => 11900000, 'harga_dasar_rupiah_fob_jakarta' => 13400000, 'distributor_origin' => 'CHINA', 'shipping_fee_by_air' => 300000, 'weight' => 5],
        ];

        foreach ($products as $productData) {
            Product::create([
                'name' => $productData['brand'].' - '.$productData['type'],
                'price' => $productData['price_list'],
                'brand' => $productData['brand'],
                'type' => $productData['type'],
                'description' => $productData['description'],
                'price' => $productData['price_list'],
                'distributor_origin' => $productData['distributor_origin'],
                'weight' => $productData['weight'],
                'shipping_fee_by_air' => $productData['shipping_fee_by_air'],
                'dollar_base_price' => $productData['harga_dasar_dolar'],
            ]);
        }

        // Create 5 Sales Users
        $salesUsers = [];
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
            ]);

            $sales->assignRole('SALES');
            $salesUsers[] = $sales;
        }

        $projectUser = [
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

        // Create project/technical users (division PTK)
        $ptkDivision = Division::where('kode', 'PTK')->first();
        foreach ($projectUser as $puser) {
            $userData = [
                'unique_id' => $puser['unique_id'],
                'no_karyawan' => $puser['no_karyawan'],
                'name' => $puser['name'],
                'email' => $puser['email'],
                'join_month' => $puser['join_month'],
                'join_year' => $puser['join_year'],
                'division_id' => $ptkDivision ? $ptkDivision->id : $division->id,
                'password' => Hash::make('12345678'),
            ];

            $projUser = User::create($userData);
            // assign PROJECT role if available
            $projUser->assignRole('PROJECT');
        }

        // Create 2 prospects and quotations for each sales user
        $prospectData = [
            [
                'customer_name' => 'Bpk Bambang Sutrisno',
                'no_handphone' => '081234567890',
                'email' => 'bambang@company1.com',
                'company' => 'PT Teknologi Maju',
                'company_identity' => 'TM-001',
            ],
            [
                'customer_name' => 'Ibu Sari Dewi',
                'no_handphone' => '081234567891',
                'email' => 'sari@company2.com',
                'company' => 'CV Digital Solutions',
                'company_identity' => 'DS-002',
            ],
        ];

        foreach ($salesUsers as $index => $sales) {
            foreach ($prospectData as $prospectIndex => $prospectInfo) {
                $prospect = Prospect::create([
                    'customer_name' => $prospectInfo['customer_name'],
                    'no_handphone' => $prospectInfo['no_handphone'],
                    'email' => str_replace('@', $index.$prospectIndex.'@', $prospectInfo['email']),
                    'company' => $prospectInfo['company'].' - Sales '.($index + 1),
                    'company_identity' => $prospectInfo['company_identity'].'-S'.($index + 1),
                    'status_id' => rand(1, 5), // Random status between 1-5
                    'target_from_month' => str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT),
                    'target_to_month' => str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT),
                    'target_from_year' => '2025',
                    'target_to_year' => '2025',
                    'note' => 'Prospect for '.$sales->name.' - '.$prospectInfo['customer_name'],
                    'pre_sales' => $sales->id,
                    'document' => 'document_'.$sales->id.'_'.$prospectIndex.'.pdf',
                    'created_by' => $sales->id,
                ]);

                // Create quotation for each prospect
                $quotation = \App\Models\Quotation::create([
                    'prospect_id' => $prospect->id,
                    'created_by' => $sales->id,
                    'revision_number' => 0,
                    'status' => 'draft',
                    'notes' => 'Initial quotation for '.$prospect->customer_name,
                    'need_accommodation' => rand(0, 1) == 1,
                    'installation_percentage' => rand(0, 1) == 1 ? rand(10, 30) : 0,
                ]);

                // Add 2-4 random products to each quotation
                $productCount = rand(2, 4);
                $randomProducts = Product::inRandomOrder()->limit($productCount)->get();

                foreach ($randomProducts as $product) {
                    \App\Models\QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'product_id' => $product->id,
                        'quantity' => rand(1, 5),
                        'unit_price' => $product->price,
                    ]);
                }

                // If installation is needed, add installation items
                if ($quotation->need_accommodation) {
                    $installations = \App\Models\Installation::inRandomOrder()->limit(rand(1, 3))->get();
                    foreach ($installations as $installation) {
                        \App\Models\QuotationInstallationItem::create([
                            'quotation_id' => $quotation->id,
                            'installation_id' => $installation->id,
                            'quantity' => 1,
                            'unit_price' => $installation->proportional * 100000, // Sample price calculation
                        ]);
                    }
                }

                // Calculate total for quotation
                $quotation->calculateTotal();

                // Create a Project linked to this Prospect
                // Map prospect fields to project fields where appropriate
                \App\Models\Project::create([
                    'client_name' => $prospect->customer_name,
                    'client_email' => $prospect->email,
                    'client_phone' => $prospect->no_handphone,
                    'company' => $prospect->company,
                    'company_identity' => $prospect->company_identity,
                    'target_from_month' => $prospect->target_from_month,
                    'target_to_month' => $prospect->target_to_month,
                    'status_id' => $prospect->status_id ?? 1,
                    'description' => 'Project created from prospect: '.$prospect->customer_name,
                    'created_by' => $sales->id,
                    'prospect_id' => $prospect->id,
                    "target_from_year"=> $prospect->target_from_year,
                    "target_to_year"=> $prospect->target_to_year,
                ]);
            }
        }
    }
}
