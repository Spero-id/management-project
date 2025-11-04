<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Website Development - Basic',
                'description' => 'Basic website with 5 pages, responsive design, and contact form',
                'price' => 15000000.00,
            ],
            [
                'name' => 'Website Development - Advanced',
                'description' => 'Advanced website with CMS, e-commerce functionality, and SEO optimization',
                'price' => 35000000.00,
            ],
            [
                'name' => 'Mobile App Development - Android',
                'description' => 'Native Android application with modern UI/UX and API integration',
                'price' => 50000000.00,
            ],
            [
                'name' => 'Mobile App Development - iOS',
                'description' => 'Native iOS application with modern UI/UX and API integration',
                'price' => 55000000.00,
            ],
            [
                'name' => 'Database Design & Development',
                'description' => 'Custom database design and development with optimization',
                'price' => 12000000.00,
            ],
            [
                'name' => 'System Integration',
                'description' => 'Integration of existing systems with new applications',
                'price' => 20000000.00,
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'Complete UI/UX design for web or mobile applications',
                'price' => 8000000.00,
            ],
            [
                'name' => 'Maintenance & Support (Monthly)',
                'description' => 'Monthly maintenance and technical support for applications',
                'price' => 2500000.00,
            ],
            [
                'name' => 'Hosting & Domain (Yearly)',
                'description' => 'Web hosting and domain registration for one year',
                'price' => 3000000.00,
            ],
            [
                'name' => 'Training & Documentation',
                'description' => 'User training and comprehensive system documentation',
                'price' => 5000000.00,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
