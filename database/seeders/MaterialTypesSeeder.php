<?php

namespace Database\Seeders;

use App\Models\MaterialType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MaterialType::create(
        [
            'name'              => 'قسم الفواتير 1',
            'status'            => ['un_active', 'active'][array_rand(['un_active', 'active'])], // Correctly get a random status
            'created_by'        => 1,
            'updated_by'        => 1,
            'company_code'      => 10001000,
        ]);

        MaterialType::create(
            [
                'name'              => 'قسم الفواتير 2',
                'status'            => ['un_active', 'active'][array_rand(['un_active', 'active'])], // Correctly get a random status
                'created_by'        => 1,
                'updated_by'        => 1,
                'company_code'      => 10001000,
            ]);

            MaterialType::create(
                [
                    'name'              => 'قسم الفواتير 3',
                    'status'            => ['un_active', 'active'][array_rand(['un_active', 'active'])], // Correctly get a random status
                    'created_by'        => 1,
                    'updated_by'        => 1,
                    'company_code'      => 10001000,
                ]);


                MaterialType::create(
                    [
                        'name'              => 'قسم الفواتير 4',
                        'status'            => ['un_active', 'active'][array_rand(['un_active', 'active'])], // Correctly get a random status
                        'created_by'        => 1,
                        'updated_by'        => 1,
                        'company_code'      => 10001000,
                    ]);



                    MaterialType::create(
                        [
                            'name'              => 'قسم الفواتير 5',
                            'status'            => ['un_active', 'active'][array_rand(['un_active', 'active'])], // Correctly get a random status
                            'created_by'        => 1,
                            'updated_by'        => 1,
                            'company_code'      => 10001000,
                        ]);





                        MaterialType::create(
                            [
                                'name'              => 'قسم الفواتير 1',
                                'status'            => ['un_active', 'active'][array_rand(['un_active', 'active'])], // Correctly get a random status
                                'created_by'        => 2,
                                'updated_by'        => 2,
                                'company_code'      => 20002000,
                            ]);

                            MaterialType::create(
                                [
                                    'name'              => 'قسم الفواتير 2',
                                    'status'            => ['un_active', 'active'][array_rand(['un_active', 'active'])], // Correctly get a random status
                                    'created_by'        => 2,
                                    'updated_by'        => 2,
                                    'company_code'      => 20002000,
                                ]);
    }
}
