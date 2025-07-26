<?php

namespace Database\Seeders;

use App\Models\ItemUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemUnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        ItemUnit::create(
            [
                'name'              => 'شيكارة',
                'status'            => 'active', // Correctly get a random status
                'is_master'         => 'master', // Correctly get a random user type
                'created_by'        => 1,
                'updated_by'        => 1,
                'company_code'      => 10001000,
            ]);
        ItemUnit::create(
            [
                'name'              => 'طن',
                'status'            => 'active', // Correctly get a random status
                'is_master'         => 'master', // Correctly get a random user type
                'created_by'        => 1,
                'updated_by'        => 1,
                'company_code'      => 10001000,
            ]);


            ItemUnit::create(
                [
                    'name'              => 'كيلو',
                    'status'            => 'active', // Correctly get a random status
                    'is_master'         => 'sub_master', // Correctly get a random user type
                    'created_by'        => 1,
                    'updated_by'        => 1,
                    'company_code'      => 10001000,
                ]);
        ItemUnit::create(
            [
                'name'              => 'كرتونة',
                'status'            => 'active', // Correctly get a random status
                'is_master'         => 'master', // Correctly get a random user type
                'created_by'        => 2,
                'updated_by'        => 2,
                'company_code'      => 20002000,
            ]);
        ItemUnit::create(
            [
                'name'              => 'كيلو',
                'status'            => 'active', // Correctly get a random status
                'is_master'         => 'sub_master', // Correctly get a random user type
                'created_by'        => 2,
                'updated_by'        => 2,
                'company_code'      => 20002000,
            ]);
        ItemUnit::create(
            [
                'name'              => 'زجاحة',
                'status'            => 'active', // Correctly get a random status
                'is_master'         => 'master', // Correctly get a random user type
                'created_by'        => 2,
                'updated_by'        => 2,
                'company_code'      => 20002000,
            ]);
        ItemUnit::create(
            [
                'name'              => 'سم',
                'status'            => 'active', // Correctly get a random status
                'is_master'         => 'sub_master', // Correctly get a random user type
                'created_by'        => 2,
                'updated_by'        => 2,
                'company_code'      => 20002000,
            ]);
        ItemUnit::create(
            [
                'name'              => 'طن',
                'status'            => 'active', // Correctly get a random status
                'is_master'         => 'master', // Correctly get a random user type
                'created_by'        => 2,
                'updated_by'        => 2,
                'company_code'      => 20002000,
            ]);
     

    }
}
