<?php

namespace Database\Seeders;

use App\Models\ItemCardMovementType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemCardMoveMentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemCardMovementType::create(
        [
            'name'              => 'اضافة الي المخزن',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 10001000,
        ]);



        ItemCardMovementType::create(
        [
            'name'              => 'صرف من المخزن',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 10001000,
        ]);





        ItemCardMovementType::create(
        [
            'name'              => 'اضافة الي المخزن',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 20002000,
        ]);



         ItemCardMovementType::create(
        [
            'name'              => 'صرف من المخزن',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 20002000,
        ]);

    }
}
