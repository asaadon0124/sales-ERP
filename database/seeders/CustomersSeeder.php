<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // شركة 1 ****************************************************
        // A - *****************************************
        Account::create(
        [
            'name'                              => 'احمد',
            'account_type_id'                   => '6',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
            'is_parent'                         => '0',         // هل الحساب اب -- نعم
            'parent_account_number'             => '1',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
            'start_balance_status'              => 'nun',       // حالة رصيد اول المدة -- متزن
            'start_balance'                     => 0,           //  رصيد اول المدة
            'current_balance'                   => 0,           //  الرصيد الفعلي
            'account_number'                    => 8,           //  رقم الحساب للحساب
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,
        ]);
        Customer::create(
        [
            'name'                              => 'احمد',
            'start_balance_status'              => 'nun',       // حالة رصيد اول المدة -- متزن
            'address'                           => 'طالبية فيصل',
            'start_balance'                     => 0,           //  رصيد اول المدة
            'current_balance'                   => 0,           //  الرصيد الفعلي
            'customer_code'                     => 1,           //  كود العميل
            'account_number'                    => 8,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,

        ]);
        //  -- *****************************************



        // B - *****************************************
        Account::create(
        [
            'name'                              => 'محمد',
            'account_type_id'                   => '6',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
            'is_parent'                         => '0',         // هل الحساب اب -- نعم
            'parent_account_number'             => '1',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
            'start_balance_status'              => 'credit',       // حالة رصيد اول المدة -- متزن
            'start_balance'                     => -200,           //  رصيد اول المدة
            'current_balance'                   => -200,           //  الرصيد الفعلي
            'account_number'                    => 9,           //  رقم الحساب للحساب
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,
        ]);
        Customer::create(
        [
            'name'                              => 'محمد',
            'start_balance_status'              => 'credit',       // حالة رصيد اول المدة -- متزن
            'address'                           => 'اكتوبر',
            'start_balance'                     => -200,           //  رصيد اول المدة
            'current_balance'                   => -200,           //  الرصيد الفعلي
            'customer_code'                     => 2,           //  كود العميل
            'account_number'                    => 8,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,

        ]);
        //  -- *****************************************


        // C - *****************************************
         Account::create(
        [
            'name'                              => 'علي',
            'account_type_id'                   => '6',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
            'is_parent'                         => '0',         // هل الحساب اب -- نعم
            'parent_account_number'             => '1',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
            'start_balance_status'              => 'debit',       // حالة رصيد اول المدة -- متزن
            'start_balance'                     => 500,           //  رصيد اول المدة
            'current_balance'                   => 500,           //  الرصيد الفعلي
            'account_number'                    => 9,           //  رقم الحساب للحساب
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,
        ]);
        Customer::create(
        [
            'name'                              => 'علي',
            'start_balance_status'              => 'debit',       // حالة رصيد اول المدة -- متزن
            'address'                           => 'اكتوبر',
            'start_balance'                     => 500,           //  رصيد اول المدة
            'current_balance'                   => 500,           //  الرصيد الفعلي
            'customer_code'                     => 3,           //  كود العميل
            'account_number'                    => 9,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,

        ]);
        //  -- *****************************************










        // شركة 2 ****************************************************
        // A - *****************************************
        Account::create(
            [
                'name'                              => 'مني',
                'account_type_id'                   => '15',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
                'is_parent'                         => '0',         // هل الحساب اب -- نعم
                'parent_account_number'             => '1',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
                'start_balance_status'              => 'nun',       // حالة رصيد اول المدة -- متزن
                'start_balance'                     => 0,           //  رصيد اول المدة
                'current_balance'                   => 0,           //  الرصيد الفعلي
                'account_number'                    => 9,           //  رقم الحساب للحساب
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,
            ]);
            Customer::create(
            [
                'name'                              => 'مني',
                'start_balance_status'              => 'nun',       // حالة رصيد اول المدة -- متزن
                'address'                           => 'طالبية فيصل',
                'start_balance'                     => 0,           //  رصيد اول المدة
                'current_balance'                   => 0,           //  الرصيد الفعلي
                'customer_code'                     => 1,           //  كود العميل
                'account_number'                    => 9,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,

            ]);
            //  -- *****************************************



             // B - *****************************************
             Account::create(
                [
                    'name'                              => 'الاء',
                    'account_type_id'                   => '15',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
                    'is_parent'                         => '0',         // هل الحساب اب -- نعم
                    'parent_account_number'             => '1',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
                    'start_balance_status'              => 'credit',       // حالة رصيد اول المدة -- متزن
                    'start_balance'                     => -400,           //  رصيد اول المدة
                    'current_balance'                   => -400,           //  الرصيد الفعلي
                    'account_number'                    => 10,           //  رقم الحساب للحساب
                    'status'                            => 'active',
                    'created_by'                        => 2,
                    'updated_by'                        => 2,
                    'company_code'                      => 20002000,
                ]);
                Customer::create(
                [
                    'name'                              => 'الاء',
                    'start_balance_status'              => 'credit',       // حالة رصيد اول المدة -- متزن
                    'address'                           => 'اكتوبر',
                    'start_balance'                     => -400,           //  رصيد اول المدة
                    'current_balance'                   => -400,           //  الرصيد الفعلي
                    'customer_code'                     => 2,           //  كود العميل
                    'account_number'                    => 10,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
                    'status'                            => 'active',
                    'created_by'                        => 2,
                    'updated_by'                        => 2,
                    'company_code'                      => 20002000,

                ]);
                //  -- *****************************************


                 // C - *****************************************
             Account::create(
                [
                    'name'                              => 'سلمي',
                    'account_type_id'                   => '15',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
                    'is_parent'                         => '0',         // هل الحساب اب -- نعم
                    'parent_account_number'             => '1',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
                    'start_balance_status'              => 'debit',       // حالة رصيد اول المدة -- متزن
                    'start_balance'                     => 800,           //  رصيد اول المدة
                    'current_balance'                   => 800,           //  الرصيد الفعلي
                    'account_number'                    => 11,           //  رقم الحساب للحساب
                    'status'                            => 'active',
                    'created_by'                        => 2,
                    'updated_by'                        => 2,
                    'company_code'                      => 20002000,
                ]);
                Customer::create(
                [
                    'name'                              => 'سلمي',
                    'start_balance_status'              => 'debit',       // حالة رصيد اول المدة -- متزن
                    'address'                           => 'اكتوبر',
                    'start_balance'                     => 800,           //  رصيد اول المدة
                    'current_balance'                   => 800,           //  الرصيد الفعلي
                    'customer_code'                     => 3,           //  كود العميل
                    'account_number'                    => 11,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
                    'status'                            => 'active',
                    'created_by'                        => 2,
                    'updated_by'                        => 2,
                    'company_code'                      => 20002000,

                ]);
                //  -- *****************************************


    }
}
