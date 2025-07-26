<?php

namespace Database\Seeders;

use App\Models\TreasuriesDetailes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TreasuriesDetailesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TreasuriesDetailes::create(
            [
                'treasuries_id'     => 1,
                'sub_treasuries_id' => 1,
                'created_by'        => 1,
                'updated_by'        => 1,
            ]);


            TreasuriesDetailes::create(
                [
                    'treasuries_id'     => 1,
                    'sub_treasuries_id' => 2,
                    'created_by'        => 1,
                    'updated_by'        => 1,
                ]);



                TreasuriesDetailes::create(
                    [
                        'treasuries_id'     => 2,
                        'sub_treasuries_id' => 1,
                        'created_by'        => 1,
                        'updated_by'        => 1,
                    ]);

                TreasuriesDetailes::create(
                    [
                        'treasuries_id'     => 2,
                        'sub_treasuries_id' => 2,
                        'created_by'        => 1,
                        'updated_by'        => 1,
                    ]);


                TreasuriesDetailes::create(
                    [
                        'treasuries_id'     => 3,
                        'sub_treasuries_id' => 3,
                        'created_by'        => 2,
                        'updated_by'        => 2,
                    ]);
                TreasuriesDetailes::create(
                    [
                        'treasuries_id'     => 3,
                        'sub_treasuries_id' => 4,
                        'created_by'        => 2,
                        'updated_by'        => 2,
                    ]);

              


    }
}
