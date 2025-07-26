<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create(
            [
                'name'              => 'مخزن 1',
                'status'            => 'active',
                'created_by'        => 1,
                'phone'             => 01234567,
                'address'           => 'طالبية جيزة',
                'updated_by'        => 1,
                'company_code'      => 10001000,

            ]);

            Store::create(
                [
                    'name'              => 'مخزن 2',
                    'status'            => 'active',
                    'created_by'        => 1,
                    'phone'             => 01234554567,
                    'address'           => 'ميامي الاسكندرية',
                    'updated_by'        => 1,
                    'company_code'      => 10001000,

                ]);

                Store::create(
                    [
                        'name'              => 'مخزن 3',
                        'status'            => 'active',
                        'created_by'        => 1,
                        'phone'             => 0102554567,
                        'address'           => 'طنطا الغربية',
                        'updated_by'        => 1,
                        'company_code'      => 10001000,
                    ]);


                    Store::create(
                        [
                            'name'              => 'مخزن 1',
                            'status'            => 'active',
                            'created_by'        => 2,
                            'phone'             => 01234567,
                            'address'           => 'طالبية فيصل',
                            'updated_by'        => 2,
                            'company_code'      => 20002000,

                        ]);

                        Store::create(
                            [
                                'name'              => 'مخزن 2',
                                'status'            => 'active',
                                'created_by'        => 2,
                                'phone'             => 01234554567,
                                'address'           => 'مندرة الاسكندرية',
                                'updated_by'        => 2,
                                'company_code'      => 20002000,
                            ]);

    }
}
