<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // شركة 1 ***************************************
       $createadmin1 =  Admin::create(
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('ahmed1191'),
                'employee_code'          => 1,
                'account_number'          => 7,
                'start_balance_status'          => 'debit',
                'start_balance'          => -1000,
                'current_balance'          => -1000,
                'company_code'          => 10001000,

            ]);
             // 3 - CREATE NEW ROLES AND PERMITIONS RELATION TABLE
                $createadmin1->syncRoles('10001000 مدير رئيسي');

            // شركة 2 ***************************************

           $createadmin21 =  Admin::create(
                [
                    'name' => 'admin1',
                    'email' => 'admin1@gmail.com',
                    'password' => bcrypt('ahmed1191'),
                    'employee_code'          => 1,
                    'account_number'          => 7,
                    'start_balance_status'          => 'debit',
                    'start_balance'          => -1000,
                    'current_balance'          => -1000,
                    'company_code'          => 20002000,

                ]);

                // 3 - CREATE NEW ROLES AND PERMITIONS RELATION TABLE
                $createadmin21->syncRoles('مدير رئيسي 20002000');

           $createadmin22 =  Admin::create(
                [
                    'name' => 'admin2',
                    'email' => 'admin2@gmail.com',
                    'password' => bcrypt('ahmed1191'),
                    'employee_code'          => 2,
                    'account_number'          => 8,
                    'start_balance_status'          => 'credit',
                    'start_balance'          => 1000,
                    'current_balance'          => 1000,
                    'company_code'          => 20002000,

                ]);

                 // 3 - CREATE NEW ROLES AND PERMITIONS RELATION TABLE
                $createadmin22->syncRoles('20002000 مدير فرعي');
    }
}
