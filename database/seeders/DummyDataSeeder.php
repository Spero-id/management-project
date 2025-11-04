<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Division;
use App\Models\ProspectStatus;
use App\Models\Prospect;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create divisions
        $divisions = [
            ['name' => 'Sales', 'description' => 'Sales Division'],
            ['name' => 'Marketing', 'description' => 'Marketing Division'],
            ['name' => 'IT', 'description' => 'Information Technology Division'],
        ];

        foreach ($divisions as $division) {
            Division::firstOrCreate(['name' => $division['name']], $division);
        }

        // Create prospect statuses
        $statuses = [
            ['name' => 'active', 'persentage' => 25, 'color' => '#28a745'],
            ['name' => 'pending', 'persentage' => 50, 'color' => '#ffc107'],
            ['name' => 'converted', 'persentage' => 100, 'color' => '#17a2b8'],
            ['name' => 'rejected', 'persentage' => 0, 'color' => '#dc3545'],
        ];

        foreach ($statuses as $status) {
            ProspectStatus::firstOrCreate(['name' => $status['name']], $status);
        }

        // Create users (sales people)
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'no_karyawan' => 'SAL001',
                'division_id' => 1,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'no_karyawan' => 'SAL002',
                'division_id' => 1,
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@example.com',
                'password' => Hash::make('password'),
                'no_karyawan' => 'SAL003',
                'division_id' => 1,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }

        // Create products
        $products = [
            ['name' => 'Software License', 'price' => 1000000, 'description' => 'Annual software license'],
            ['name' => 'Hardware Installation', 'price' => 5000000, 'description' => 'Complete hardware setup'],
            ['name' => 'Consulting Service', 'price' => 2500000, 'description' => 'IT consulting service'],
            ['name' => 'Maintenance Package', 'price' => 750000, 'description' => 'Monthly maintenance'],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['name' => $product['name']], $product);
        }

        // Get created data
        $salesUsers = User::where('division_id', 1)->get();
        $prospectStatuses = ProspectStatus::all();
        $products = Product::all();

        // Create prospects
        $companies = [
            ['name' => 'PT Teknologi Maju', 'identity' => 'TM'],
            ['name' => 'CV Digital Solution', 'identity' => 'DS'],
            ['name' => 'PT Inovasi Sistem', 'identity' => 'IS'],
            ['name' => 'PT Global Tech', 'identity' => 'GT'],
            ['name' => 'CV Smart Business', 'identity' => 'SB'],
            ['name' => 'PT Future IT', 'identity' => 'FIT'],
            ['name' => 'CV Modern Systems', 'identity' => 'MS'],
            ['name' => 'PT Digital Enterprise', 'identity' => 'DE'],
        ];

        $prospects = [];
        foreach ($companies as $index => $company) {
            $prospect = Prospect::create([
                'customer_name' => 'Manager ' . $company['name'],
                'no_handphone' => '08' . rand(1000000000, 9999999999),
                'email' => strtolower(str_replace(' ', '', $company['name'])) . '@example.com',
                'company' => $company['name'],
                'company_identity' => $company['identity'],
                'status_id' => $prospectStatuses->random()->id,
                'target_from_month' => rand(1, 12),
                'target_to_month' => rand(1, 12),
                'note' => 'Prospek dari ' . $company['name'],
                'pre_sales' => $salesUsers->random()->id,
                'created_by' => $salesUsers->random()->id,
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
            $prospects[] = $prospect;
        }

        // Create quotations
        $quotationStatuses = ['draft', 'sent', 'approved', 'rejected'];
        
        foreach ($prospects as $index => $prospect) {
            // Create 1-3 quotations per prospect
            $quotationCount = rand(1, 3);
            
            for ($i = 0; $i < $quotationCount; $i++) {
                $quotation = Quotation::create([
                    'prospect_id' => $prospect->id,
                    'status' => $quotationStatuses[array_rand($quotationStatuses)],
                    'notes' => 'Quotation untuk ' . $prospect->company,
                    'created_at' => now()->subDays(rand(1, 20)),
                ]);

                // Add quotation items
                $itemCount = rand(1, 4);
                $totalAmount = 0;

                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    $quantity = rand(1, 5);
                    $unitPrice = $product->price;
                    $subtotal = $quantity * $unitPrice;
                    $totalAmount += $subtotal;

                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ]);
                }

                // Update quotation total
                $quotation->update(['total_amount' => $totalAmount]);
            }
        }

        $this->command->info('Dummy data created successfully!');
        $this->command->info('Created:');
        $this->command->info('- ' . count($divisions) . ' divisions');
        $this->command->info('- ' . count($statuses) . ' prospect statuses');
        $this->command->info('- ' . count($users) . ' users');
        $this->command->info('- ' . count($products) . ' products');
        $this->command->info('- ' . count($prospects) . ' prospects');
        $this->command->info('- ' . Quotation::count() . ' quotations');
        $this->command->info('- ' . QuotationItem::count() . ' quotation items');
    }
}