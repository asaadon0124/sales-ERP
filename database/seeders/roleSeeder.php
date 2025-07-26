<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class roleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Role::create(
            [
                'name'         => '10001000 مدير رئيسي',
                'guard_name'   => 'admin',
                'created_by'   => 1,
                'updated_by'   => 1,
                'company_code' => 10001000,

            ]);

         Role::create(
            [
                'name'         => '10001000مدير فرعي',
                'guard_name'   => 'admin',
                'created_by'   => 1,
                'updated_by'   => 1,
                'company_code' => 10001000,

            ]);


         Role::create(
            [
                'name'         => 'مدير رئيسي 20002000',
                'guard_name'   => 'admin',
                'created_by'   => 2,
                'updated_by'   => 2,
                'company_code' => 20002000,

            ]);

         Role::create(
            [
                'name'         => '20002000 مدير فرعي',
                'guard_name'   => 'admin',
                'created_by'   => 2,
                'updated_by'   => 2,
                'company_code' => 20002000,
            ]);
    }
}
