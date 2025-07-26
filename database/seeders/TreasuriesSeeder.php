<?php

namespace Database\Seeders;

use App\Models\Treasuries;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TreasuriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Treasuries::create(
            [
               'name'              => 'خزنة 1',
                'status'            => ['un_active', 'active'][array_rand(['un_active', 'active'])], // Correctly get a random status
                'is_master'         => ['master', 'user'][array_rand(['master', 'user'])], // Correctly get a random user type
                'last_recept_pay'   => 0,
                'last_recept_recive'=> 0,
                'created_by'        => 1,
                'updated_by'        => 1,
                'company_code'      => 10001000,
            ]);




            Treasuries::create(
                [
                   'name'              => 'خزنة 2',
                    'status'            => ['un_active', 'active'][array_rand(['un_active', 'active'])], // Correctly get a random status
                    'is_master'         => ['master', 'user'][array_rand(['master', 'user'])], // Correctly get a random user type
                    'last_recept_pay'   => 0,
                    'last_recept_recive'=> 0,
                    'created_by'        => 1,
                    'updated_by'        => 1,
                    'company_code'      => 10001000,
                ]);


                Treasuries::create(
                    [
                       'name'              => 'خزنة 1',
                        'status'            => 'active', // Correctly get a random status
                        'is_master'         => 'master',// Correctly get a random user type
                        'last_recept_pay'   => 0,
                        'last_recept_recive'=> 0,
                        'created_by'        => 2,
                        'updated_by'        => 2,
                        'company_code'      => 20002000,
                    ]);



                 Treasuries::create(
                    [
                       'name'              => 'خزنة 2',
                        'status'            => 'active', // Correctly get a random status
                        'is_master'         => 'user',
                        'last_recept_pay'   => 0,
                        'last_recept_recive'=> 0,
                        'created_by'        => 2,
                        'updated_by'        => 2,
                        'company_code'      => 20002000,
                    ]);
    }
}
