<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1
        AccountType::create(
        [
            'name'                          => 'عام',
            'status'                        => 'active',
            'created_by'                    => 1,
            'updated_by'                    => 1,
            'company_code'                  => 10001000,
        ]);

        // 2
        AccountType::create(
            [
                'name'                          => 'راس المال',
                'status'                        => 'active',
                'created_by'                    => 1,
                'updated_by'                    => 1,
                'company_code'                  => 10001000,
            ]);

            // 3
            AccountType::create(
                [
                    'name'                          => 'مصروفات',
                    'status'                        => 'active',
                    'created_by'                    => 1,
                    'updated_by'                    => 1,
                    'company_code'                  => 10001000,
                ]);

                // 4
                 AccountType::create(
                [
                    'name'                          => 'بنك',
                    'status'                        => 'active',
                    'created_by'                    => 1,
                    'updated_by'                    => 1,
                    'company_code'                  => 10001000,
                ]);
                // 5
            AccountType::create(
                [
                    'name'                          => 'مورد',
                    'status'                        => 'active',
                    'created_by'                    => 1,
                    'updated_by'                    => 1,
                    'company_code'                  => 10001000,
                ]);
                // 6
            AccountType::create(
                [
                    'name'                          => 'عميل',
                    'status'                        => 'active',
                    'created_by'                    => 1,
                    'updated_by'                    => 1,
                    'company_code'                  => 10001000,
                ]);
                // 7
            AccountType::create(
                [
                    'name'                          => 'مندوب',
                    'status'                        => 'active',
                    'created_by'                    => 1,
                    'updated_by'                    => 1,
                    'company_code'                  => 10001000,
                ]);
                // 8
            AccountType::create(
                [
                    'name'                          => 'موظف',
                    'status'                        => 'active',
                    'created_by'                    => 1,
                    'updated_by'                    => 1,
                    'company_code'                  => 10001000,
                ]);
                // 9
            AccountType::create(
                [
                    'name'                          => 'قسم داخلي',
                    'status'                        => 'active',
                    'created_by'                    => 1,
                    'updated_by'                    => 1,
                    'company_code'                  => 10001000,
                ]);




                // شركة 2 ************************************************

                // 10
                AccountType::create(
                [
                    'name'                          => 'عام',
                    'status'                        => 'active',
                    'created_by'                    => 2,
                    'updated_by'                    => 1,
                    'company_code'                  => 20002000,
                ]);

                // 11
        AccountType::create(
            [
                'name'                          => 'راس المال',
                'status'                        => 'active',
                'created_by'                    => 2,
                'updated_by'                    => 1,
                'company_code'                  => 20002000,
            ]);

            // 12
            AccountType::create(
                [
                    'name'                          => 'مصروفات',
                    'status'                        => 'active',
                    'created_by'                    => 2,
                    'updated_by'                    => 1,
                    'company_code'                  => 20002000,
                ]);

                // 13
                 AccountType::create(
                [
                    'name'                          => 'بنك',
                    'status'                        => 'active',
                    'created_by'                    => 2,
                    'updated_by'                    => 1,
                    'company_code'                  => 20002000,
                ]);


                // 14
            AccountType::create(
                [
                    'name'                          => 'مورد',
                    'status'                        => 'active',
                    'created_by'                    => 2,
                    'updated_by'                    => 1,
                    'company_code'                  => 20002000,
                ]);

                // 15
            AccountType::create(
                [
                    'name'                          => 'عميل',
                    'status'                        => 'active',
                    'created_by'                    => 2,
                    'updated_by'                    => 1,
                    'company_code'                  => 20002000,
                ]);

                // 16
            AccountType::create(
                [
                    'name'                          => 'مندوب',
                    'status'                        => 'active',
                    'created_by'                    => 2,
                    'updated_by'                    => 1,
                    'company_code'                  => 20002000,
                ]);

                // 17
            AccountType::create(
                [
                    'name'                          => 'موظف',
                    'status'                        => 'active',
                    'created_by'                    => 2,
                    'updated_by'                    => 1,
                    'company_code'                  => 20002000,
                ]);

                // 18
            AccountType::create(
                [
                    'name'                          => 'قسم داخلي',
                    'status'                        => 'active',
                    'created_by'                    => 2,
                    'updated_by'                    => 1,
                    'company_code'                  => 20002000,
                ]);


    }
}
