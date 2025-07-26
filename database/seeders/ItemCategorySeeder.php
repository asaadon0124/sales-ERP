<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemCategory::create(
            [
                'name'              => 'علف',
                'status'            => 'active',
                'created_by'        => 1,
                'updated_by'        => 1,
                'company_code'      => 10001000,
            ]);
        ItemCategory::create(
            [
                'name'              => 'سماد',
                'status'            => 'active',
                'created_by'        => 1,
                'updated_by'        => 1,
                'company_code'      => 10001000,
            ]);
        ItemCategory::create(
            [
                'name'              => 'بقوليات',
                'status'            => 'active',
                'created_by'        => 2,
                'updated_by'        => 2,
                'company_code'      => 20002000,
            ]);

        ItemCategory::create(
            [
                'name'              => 'مود غذائية',
                'status'            => 'active',
                'created_by'        => 2,
                'updated_by'        => 2,
                'company_code'      => 20002000,
            ]);

        ItemCategory::create(
            [
                'name'              => 'ادوية بودرة',
                'status'            => 'active',
                'created_by'        => 2,
                'updated_by'        => 2,
                'company_code'      => 20002000,
            ]);

        ItemCategory::create(
            [
                'name'              => 'ادوية سائلة',
                'status'            => 'active',
                'created_by'        => 2,
                'updated_by'        => 2,
                'company_code'      => 20002000,
            ]);
    }
}
