<?php

namespace Database\Seeders;

use App\Models\SupplierCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuppliersCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SupplierCategory::create(
        [
            'name'                      => 'موردين اعلاف',
            'status'                    => 'active',
                'created_by'            => 1,
                'updated_by'            => 1,
                'company_code'          => 10001000,
        ]);



        SupplierCategory::create(
        [
            'name'                      => 'موردين سماد',
            'status'                    => 'active',
                'created_by'            => 1,
                'updated_by'            => 1,
                'company_code'          => 10001000,
        ]);




        SupplierCategory::create(
        [
            'name'                      => 'موردين بقوليات',
            'status'                    => 'active',
                'created_by'            => 2,
                'updated_by'            => 2,
                'company_code'          => 20002000,
        ]);


        SupplierCategory::create(
        [
            'name'                      => 'موردين مواد غذائية',
            'status'                    => 'active',
                'created_by'            => 2,
                'updated_by'            => 2,
                'company_code'          => 20002000,
        ]);


        SupplierCategory::create(
        [
            'name'                      => 'ادوبة مستوردة سائلة',
            'status'                    => 'active',
                'created_by'            => 2,
                'updated_by'            => 2,
                'company_code'          => 20002000,
        ]);



        SupplierCategory::create(
        [
            'name'                      => 'ادوية محلية سائلة',
            'status'                    => 'active',
                'created_by'            => 2,
                'updated_by'            => 2,
                'company_code'          => 20002000,
        ]);

        SupplierCategory::create(
        [
            'name'                      => 'ادوية مستوردة بودرة',
            'status'                    => 'active',
                'created_by'            => 2,
                'updated_by'            => 2,
                'company_code'          => 20002000,
        ]);

        SupplierCategory::create(
        [
            'name'                      => 'ادوية محلية بودرة',
            'status'                    => 'active',
                'created_by'            => 2,
                'updated_by'            => 2,
                'company_code'          => 20002000,
        ]);
    }
}
