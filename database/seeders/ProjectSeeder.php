<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Prospect;
use App\Models\ProspectStatus;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\ProjectOrder;
use App\Models\ProjectOrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;

final class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the "on-going" status
        $ongoingStatus = ProspectStatus::where('name', 'On-going')->first();
        if (!$ongoingStatus) {
            $ongoingStatus = ProspectStatus::create([
                'name' => 'On-going',
                'persentage' => 50,
                'color' => '#ffc107'
            ]);
        }

        // Get first user as creator
        $user = User::first();

        // Create prospects for the projects
        $prospect1 = Prospect::create([
            'customer_name' => 'PT Teknologi Maju',
            'no_handphone' => '081234567890',
            'email' => 'info@teknologimaju.com',
            'company' => 'PT Teknologi Maju',
            'company_identity' => 'NPWP123456789',
            'status_id' => $ongoingStatus->id,
            'target_from_month' => 12,
            'target_to_month' => 3,
            'target_from_year' => 2024,
            'target_to_year' => 2025,
            'note' => 'Proyek infrastruktur IT untuk kantor pusat',
            'pre_sales' => $user->id,
            'created_by' => $user->id,
            'is_empty' => false,
            'product_offered' => 'Server dan networking equipment'
        ]);

        $prospect2 = Prospect::create([
            'customer_name' => 'CV Digital Solutions',
            'no_handphone' => '087654321098',
            'email' => 'contact@digitalsol.com',
            'company' => 'CV Digital Solutions',
            'company_identity' => 'NPWP987654321',
            'status_id' => $ongoingStatus->id,
            'target_from_month' => 1,
            'target_to_month' => 6,
            'target_from_year' => 2025,
            'target_to_year' => 2025,
            'note' => 'Upgrade sistem keamanan dan monitoring',
            'pre_sales' => $user->id,
            'created_by' => $user->id,
            'is_empty' => false,
            'product_offered' => 'Security systems dan monitoring tools'
        ]);

        // Create Project 1
        $project1 = Project::create([
            'client_name' => $prospect1->customer_name,
            'client_email' => $prospect1->email,
            'client_phone' => $prospect1->no_handphone,
            'company' => $prospect1->company,
            'company_identity' => $prospect1->company_identity,
            'project_name' => 'Implementasi Infrastruktur IT Kantor Pusat',
            'no_po' => 'PO-2024-001',
            'description' => 'Proyek implementasi infrastruktur IT lengkap untuk kantor pusat PT Teknologi Maju',
            'created_by' => $user->id,
            'prospect_id' => $prospect1->id,
            'execution_time' => '6 bulan',
            'status_id' => $ongoingStatus->id
        ]);

        // Create Project 2
        $project2 = Project::create([
            'client_name' => $prospect2->customer_name,
            'client_email' => $prospect2->email,
            'client_phone' => $prospect2->no_handphone,
            'company' => $prospect2->company,
            'company_identity' => $prospect2->company_identity,
            'project_name' => 'Upgrade Sistem Keamanan Digital',
            'no_po' => 'PO-2024-002',
            'description' => 'Upgrade sistem keamanan dan implementasi monitoring tools untuk CV Digital Solutions',
            'created_by' => $user->id,
            'prospect_id' => $prospect2->id,
            'execution_time' => '4 bulan',
            'status_id' => $ongoingStatus->id
        ]);

        // Create quotations for projects
        $quotation1 = Quotation::create([
            'prospect_id' => $prospect1->id,
            'created_by' => $user->id,
            'quotation_number' => 'QUO-2024-001',
            'revision_number' => 1,
            'total_amount' => 150000000.00,
            'status' => 'approved',
            'notes' => 'Quotation untuk infrastruktur IT lengkap',
            'need_accommodation' => false,
            'installation_percentage' => 15.00
        ]);

        $quotation2 = Quotation::create([
            'prospect_id' => $prospect2->id,
            'created_by' => $user->id,
            'quotation_number' => 'QUO-2024-002',
            'revision_number' => 1,
            'total_amount' => 85000000.00,
            'status' => 'approved',
            'notes' => 'Quotation untuk upgrade sistem keamanan',
            'need_accommodation' => false,
            'installation_percentage' => 10.00
        ]);

        // Create quotation items with product ID 10
        $quotationItem1 = QuotationItem::create([
            'quotation_id' => $quotation1->id,
            'product_id' => 10,
            'quantity' => 5,
            'unit_price' => 25000000.00,
            'subtotal' => 125000000.00
        ]);

        $quotationItem2 = QuotationItem::create([
            'quotation_id' => $quotation2->id,
            'product_id' => 10,
            'quantity' => 3,
            'unit_price' => 25000000.00,
            'subtotal' => 75000000.00
        ]);

        // Create project orders
        $projectOrder1 = ProjectOrder::create([
            'project_id' => $project1->id,
            'is_confirmed' => true
        ]);

        $projectOrder2 = ProjectOrder::create([
            'project_id' => $project2->id,
            'is_confirmed' => true
        ]);

        // Create project order items
        ProjectOrderItem::create([
            'project_id' => $project1->id,
            'project_order_id' => $projectOrder1->id,
            'product_id' => 10,
            'quotation_item_id' => $quotationItem1->id,
            'required_qty' => 5,
            'stock_used' => 0,
            'delivery_qty' => 0,
            'estimated_arrival_date' => now()->addDays(30),
            'order_status' => 'pending',
            'po_number' => 'PO-ORD-2024-001'
        ]);

        ProjectOrderItem::create([
            'project_id' => $project2->id,
            'project_order_id' => $projectOrder2->id,
            'product_id' => 10,
            'quotation_item_id' => $quotationItem2->id,
            'required_qty' => 3,
            'stock_used' => 0,
            'delivery_qty' => 0,
            'estimated_arrival_date' => now()->addDays(45),
            'order_status' => 'pending',
            'po_number' => 'PO-ORD-2024-002'
        ]);
    }
}