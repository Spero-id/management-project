<?php

namespace Database\Seeders;

use App\Models\Installation;
use App\Models\Product;
use App\Models\Prospect;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

final class ProspectSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $salesUsers = User::whereHas('roles', fn($query) => $query->where('name', 'SALES'))->get();

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

        // Create 2 prospects and quotations for each sales user
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
                    'is_empty' => false,
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

                $quotation->update([
                    'quotation_number' => $quotation->generateQuotationNumber(),
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
                    $installations = Installation::inRandomOrder()->limit(rand(1, 3))->get();
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
                $projectNumber = str_pad(($index * 2) + $prospectIndex + 1, 4, '0', STR_PAD_LEFT);
                $project = Project::create([
                    'project_name' => 'Project for '.$prospect->customer_name,
                    'client_name' => $prospect->customer_name,
                    'client_email' => $prospect->email,
                    'client_phone' => $prospect->no_handphone,
                    'company' => $prospect->company,
                    'company_identity' => $prospect->company_identity,
                    'project_name' => 'Project for '.$prospect->customer_name,
                    'no_po' => 'PO-2025-'.$projectNumber,
                    'execution_time' => random_int(10, 100),
                    'description' => 'Project created from prospect: '.$prospect->customer_name,
                    'created_by' => $sales->id,
                    'prospect_id' => $prospect->id,
                ]);
            }
        }

        // Create Project Orders for the first 3 projects
        $projects = Project::limit(3)->get();

        foreach ($projects as $projectIndex => $project) {
            // Create project order (alternating between confirmed and unconfirmed)
            $isConfirmed = 0;

            $projectOrder = \App\Models\ProjectOrder::create([
                'project_id' => $project->id,
                'is_confirmed' => $isConfirmed,
            ]);

            // Get quotation items for this project's prospect
            $quotationItems = \App\Models\QuotationItem::whereHas('quotation', function ($query) use ($project) {
                $query->where('prospect_id', $project->prospect_id);
            })->get();

            // Create project order items
            foreach ($quotationItems as $quotationItem) {
                \App\Models\ProjectOrderItem::create([
                    'project_order_id' => $projectOrder->id,
                    'project_id' => $project->id,
                    'product_id' => $quotationItem->product_id,
                    'quotation_item_id' => $quotationItem->id,
                    'required_qty' => $quotationItem->quantity,
                    'stock_used' => rand(0, $quotationItem->quantity),
                    'estimated_arrival_date' => now()->addDays(rand(7, 30)),
                    'order_status' => $isConfirmed ? 'complete' : 'pending',
                    'po_number' => $isConfirmed ? 'PO-'.date('Y').'-'.str_pad($projectIndex + 1, 4, '0', STR_PAD_LEFT) : null,
                    'po_file_path' => $isConfirmed ? 'po_files/po_'.$project->id.'_'.time().'.pdf' : null,
                ]);
            }
        }
    }
}