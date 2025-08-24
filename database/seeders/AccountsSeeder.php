<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // شركة 1***************************************************
        // 1 -
        Account::create(
        [
            'name'                  => 'حساب العملاء العام',
            'account_type_id'       => '6',     // نوع الحساب
            'is_parent'             => '1',     // هل الحساب اب -- نعم
            'start_balance_status'  => 'nun',   //  حالة رصيد الحساب
            'start_balance'         => 0,       // رصيد الحساب اول المدة
            'current_balance'       => 0,       // رصيد الحساب الحالي
            'account_number'        => 1,       // رقم الحساب الالي
            'status'                => 'active',
            'created_by'            => 1,
            'updated_by'            => 1,
            'company_code'          => 10001000,
        ]);


        // 2 -
        Account::create(
        [
            'name'                  => 'حساب الموردين العام',
            'account_type_id'       => '5',     // نوع الحساب
            'is_parent'             => '1',     // هل الحساب اب -- نعم
            'start_balance_status'  => 'nun',   //  حالة رصيد الحساب
            'start_balance'         => 0,       // رصيد الحساب اول المدة
            'current_balance'       => 0,       // رصيد الحساب الحالي
            'account_number'        => 2,       // رقم الحساب الالي
            'status'                => 'active',
            'created_by'            => 1,
            'updated_by'            => 1,
            'company_code'          => 10001000,
        ]);

        // 3 -
        Account::create(
        [
            'name'                  => 'حساب الموظقين العام',
            'account_type_id'       => '8',     // نوع الحساب
            'is_parent'             => '1',     // هل الحساب اب -- نعم
            'start_balance_status'  => 'nun',   //  حالة رصيد الحساب
            'start_balance'         => 0,       // رصيد الحساب اول المدة
            'current_balance'       => 0,       // رصيد الحساب الحالي
            'account_number'        => 3,       // رقم الحساب الالي
            'status'                => 'active',
            'created_by'            => 1,
            'updated_by'            => 1,
            'company_code'          => 10001000,
        ]);

        // 4
        Account::create(
        [
            'name'                  => 'حساب المناديب العام',
            'account_type_id'       => '7',     // نوع الحساب
            'is_parent'             => '1',     // هل الحساب اب -- نعم
            'start_balance_status'  => 'nun',   //  حالة رصيد الحساب
            'start_balance'         => 0,       // رصيد الحساب اول المدة
            'current_balance'       => 0,       // رصيد الحساب الحالي
            'account_number'        => 4,       // رقم الحساب الالي
            'status'                => 'active',
            'created_by'            => 1,
            'updated_by'            => 1,
            'company_code'          => 10001000,
        ]);

        // 5
            Account::create(
            [
                'name'                  => 'حساب المصروفات الاب',
                'account_type_id'       => '3',         // نوع الحساب عام
                'is_parent'             => '1',         // هل الحساب اب -- نعم
                'start_balance_status'  => 'credit',   //  حالة رصيد الحساب
                'start_balance'         => 300,           // رصيد الحساب اول المدة
                'current_balance'       => 300,           // رصيد الحساب الحالي
                'account_number'        => 5,           // رقم الحساب الالي
                'status'                => 'active',
                'created_by'            => 1,
                'updated_by'            => 1,
                'company_code'          => 10001000,
            ]);


            // 6
        Account::create(
        [
            'name'                          => 'المصروفات الفرعية',
            'account_type_id'               => '3',         // نوع الحساب عام
            'is_parent'                     => '0',         // هل الحساب اب -- لا
            'parent_account_number'         => '5',         // رقم ال account number الخاص بالحساب الاب الذي تم اختياره
            'start_balance_status'          => 'nun',     //  حالة رصيد الحساب
            'start_balance'                 => 0,       // رصيد الحساب اول المدة
            'current_balance'               => 0,       // رصيد الحساب الحالي
            'account_number'                => 6,           // رقم الحساب الالي
            'status'                        => 'active',
            'created_by'                    => 1,
            'updated_by'                    => 1,
            'company_code'                  => 10001000,

        ]);


        // 7
        Account::create(
        [
            'name'                          => 'admin',
            'account_type_id'               => '8',         // نوع الحساب
            'is_parent'                     => '0',         // هل الحساب اب -- لا
            'parent_account_number'         => '3',         // رقم ال account number الخاص بالحساب الاب الذي تم اختياره
            'start_balance_status'          => 'debit',     //  حالة رصيد الحساب
            'start_balance'                 => -1000,       // رصيد الحساب اول المدة
            'current_balance'               => -1000,       // رصيد الحساب الحالي
            'account_number'                => 7,           // رقم الحساب الالي
            'status'                        => 'active',
            'created_by'                    => 1,
            'updated_by'                    => 1,
            'company_code'                  => 10001000,

        ]);




         // 18
        Account::create(
        [
            'name'                          => 'راس المال العام' ,
            'account_type_id'               => '2',         // نوع الحساب
            'is_parent'                     => '1',         // هل الحساب اب -- لا
            'start_balance_status'          => 'nun',     //  حالة رصيد الحساب
            'start_balance'                 => 0,       // رصيد الحساب اول المدة
            'current_balance'               => 0,       // رصيد الحساب الحالي
            'account_number'                => 8,           // رقم الحساب الالي
            'status'                        => 'active',
            'created_by'                    => 1,
            'updated_by'                    => 1,
            'company_code'                  => 10001000,

        ]);

        // 19
        Account::create(
        [
            'name'                          => 'راس المال',
            'account_type_id'               => '2',         // نوع الحساب
            'is_parent'                     => '0',         // هل الحساب اب -- لا
            'parent_account_number'         => '8',         // رقم ال account number الخاص بالحساب الاب الذي تم اختياره
            'start_balance_status'          => 'nun',     //  حالة رصيد الحساب
            'start_balance'                 => 0,       // رصيد الحساب اول المدة
            'current_balance'               => 0,       // رصيد الحساب الحالي
            'account_number'                => 9,           // رقم الحساب الالي
            'status'                        => 'active',
            'created_by'                    => 1,
            'updated_by'                    => 1,
            'company_code'                  => 10001000,

        ]);

        // شركة 2***************************************************

        // 1 -
        Account::create(
        [
            'name'                  => 'حساب العملاء العام',
            'account_type_id'       => '15',     // نوع الحساب
            'is_parent'             => '1',     // هل الحساب اب -- نعم
            'start_balance_status'  => 'nun',   //  حالة رصيد الحساب
            'start_balance'         => 0,       // رصيد الحساب اول المدة
            'current_balance'       => 0,       // رصيد الحساب الحالي
            'account_number'        => 1,       // رقم الحساب الالي
            'status'                => 'active',
            'created_by'            => 2,
            'updated_by'            => 2,
            'company_code'          => 20002000,
        ]);


        // 2 -
        Account::create(
        [
            'name'                  => 'حساب الموردين العام',
            'account_type_id'       => '14',     // نوع الحساب
            'is_parent'             => '1',     // هل الحساب اب -- نعم
            'start_balance_status'  => 'nun',   //  حالة رصيد الحساب
            'start_balance'         => 0,       // رصيد الحساب اول المدة
            'current_balance'       => 0,       // رصيد الحساب الحالي
            'account_number'        => 2,       // رقم الحساب الالي
            'status'                => 'active',
            'created_by'            => 2,
            'updated_by'            => 2,
            'company_code'          => 20002000,
        ]);

        // 3 -
        Account::create(
        [
            'name'                  => 'حساب الموظقين العام',
            'account_type_id'       => '17',     // نوع الحساب
            'is_parent'             => '1',     // هل الحساب اب -- نعم
            'start_balance_status'  => 'nun',   //  حالة رصيد الحساب
            'start_balance'         => 0,       // رصيد الحساب اول المدة
            'current_balance'       => 0,       // رصيد الحساب الحالي
            'account_number'        => 3,       // رقم الحساب الالي
            'status'                => 'active',
            'created_by'            => 2,
            'updated_by'            => 2,
            'company_code'          => 20002000,
        ]);

        // 4
        Account::create(
        [
            'name'                  => 'حساب المناديب العام',
            'account_type_id'       => '16',     // نوع الحساب
            'is_parent'             => '1',     // هل الحساب اب -- نعم
            'start_balance_status'  => 'nun',   //  حالة رصيد الحساب
            'start_balance'         => 0,       // رصيد الحساب اول المدة
            'current_balance'       => 0,       // رصيد الحساب الحالي
            'account_number'        => 4,       // رقم الحساب الالي
            'status'                => 'active',
            'created_by'            => 2,
            'updated_by'            => 2,
            'company_code'          => 20002000,
        ]);

        // 5
            Account::create(
            [
                'name'                  => 'حساب المصروفات الاب',
                'account_type_id'       => '12',         // نوع الحساب عام
                'is_parent'             => '1',         // هل الحساب اب -- نعم
                'start_balance_status'  => 'credit',   //  حالة رصيد الحساب
                'start_balance'         => 300,           // رصيد الحساب اول المدة
                'current_balance'       => 300,           // رصيد الحساب الحالي
                'account_number'        => 5,           // رقم الحساب الالي
                'status'                => 'active',
                'created_by'            => 2,
                'updated_by'            => 2,
                'company_code'          => 20002000,
            ]);


            // 6
        Account::create(
        [
            'name'                          => 'المصروفات الفرعية',
            'account_type_id'               => '12',         // نوع الحساب عام
            'is_parent'                     => '0',         // هل الحساب اب -- لا
            'parent_account_number'         => '5',         // رقم ال account number الخاص بالحساب الاب الذي تم اختياره
            'start_balance_status'          => 'nun',     //  حالة رصيد الحساب
            'start_balance'                 => 0,       // رصيد الحساب اول المدة
            'current_balance'               => 0,       // رصيد الحساب الحالي
            'account_number'                => 6,           // رقم الحساب الالي
            'status'                        => 'active',
            'created_by'                    => 2,
            'updated_by'                    => 2,
            'company_code'                  => 20002000,

        ]);


        // 7
        Account::create(
        [
            'name'                          => 'admin1',
            'account_type_id'               => '17',         // نوع الحساب
            'is_parent'                     => '0',         // هل الحساب اب -- لا
            'parent_account_number'         => '3',         // رقم ال account number الخاص بالحساب الاب الذي تم اختياره
            'start_balance_status'          => 'debit',     //  حالة رصيد الحساب
            'start_balance'                 => -1000,       // رصيد الحساب اول المدة
            'current_balance'               => -1000,       // رصيد الحساب الحالي
            'account_number'                => 7,           // رقم الحساب الالي
            'status'                        => 'active',
            'created_by'                    => 2,
            'updated_by'                    => 2,
            'company_code'                  => 20002000,

        ]);
        // 7
        Account::create(
        [
            'name'                          => 'admin2',
            'account_type_id'               => '17',         // نوع الحساب
            'is_parent'                     => '0',         // هل الحساب اب -- لا
            'parent_account_number'         => '3',         // رقم ال account number الخاص بالحساب الاب الذي تم اختياره
            'start_balance_status'          => 'credit',     //  حالة رصيد الحساب
            'start_balance'                 => 1000,       // رصيد الحساب اول المدة
            'current_balance'               => 1000,       // رصيد الحساب الحالي
            'account_number'                => 8,           // رقم الحساب الالي
            'status'                        => 'active',
            'created_by'                    => 2,
            'updated_by'                    => 2,
            'company_code'                  => 20002000,

        ]);

         // 18
        Account::create(
        [
            'name'                          => 'راس المال العام' ,
            'account_type_id'               => '11',         // نوع الحساب
            'is_parent'                     => '1',         // هل الحساب اب -- لا
            'start_balance_status'          => 'nun',     //  حالة رصيد الحساب
            'start_balance'                 => 0,       // رصيد الحساب اول المدة
            'current_balance'               => 0,       // رصيد الحساب الحالي
            'account_number'                => 18,           // رقم الحساب الالي
            'status'                        => 'active',
            'created_by'                    => 2,
            'updated_by'                    => 2,
            'company_code'                  => 20002000,

        ]);

        // 19
        Account::create(
        [
            'name'                          => 'راس المال',
            'account_type_id'               => '11',         // نوع الحساب
            'is_parent'                     => '0',         // هل الحساب اب -- لا
            'parent_account_number'         => '18',         // رقم ال account number الخاص بالحساب الاب الذي تم اختياره
            'start_balance_status'          => 'nun',     //  حالة رصيد الحساب
            'start_balance'                 => 0,       // رصيد الحساب اول المدة
            'current_balance'               => 0,       // رصيد الحساب الحالي
            'account_number'                => 19,           // رقم الحساب الالي
            'status'                        => 'active',
            'created_by'                    => 2,
            'updated_by'                    => 2,
            'company_code'                  => 20002000,

        ]);
    }
}
