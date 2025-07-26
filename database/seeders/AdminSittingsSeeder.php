<?php

namespace Database\Seeders;

use App\Models\AdminSitting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSittingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminSitting::create(
        [
            'system_name'           => 'Company 1',
            'phone'                 => '01234567891',
            'photo'                 => 'adminStiitngs/logo.png',
            'status'                => 'active',
            'address'               => 'طالبية - فيصل - الجيزة',
            // 'company_code'          => rand(1,999999),
            'company_code'          => 10001000,
            'created_by'            => 1,
            'customer_parent_account_number'          => 1,
            'supplier_parent_account_number'          => 2,
            'servant_parent_account_number'          => 4,
            'employee_parent_account_number'          => 3,
        ]);


        AdminSitting::create(
            [
                'system_name'                       => 'Company 2',
                'phone'                             => '012345678910',
                'photo'                             => 'adminStiitngs/logo.png',
                'status'                            => 'active',
                'address'                           => 'طالبية - فيصل - الجيزة',
                'company_code'                      => 20002000,
                'created_by'                        => 2,
                'customer_parent_account_number'    => 1,
                'supplier_parent_account_number'    => 2,
                'servant_parent_account_number'     => 4,
                'employee_parent_account_number'    => 3,
            ]);
    }
}
