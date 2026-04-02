<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Reports',
            'Contracts',
            'Invoices',
            'Policies',
            'HR Documents',
            'Technical Documentation',
            'Marketing Materials',
            'Legal Documents',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
