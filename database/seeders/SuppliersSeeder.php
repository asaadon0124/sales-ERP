<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuppliersSeeder extends Seeder
{

    public function run(): void
    {
         // شركة 1 ****************************************************
        // A - *****************************************
        Account::create(
        [
            'name'                              => 'مريم مورد',
            'account_type_id'                   => '5',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
            'is_parent'                         => '0',         // هل الحساب اب -- نعم
            'parent_account_number'             => '2',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
            'start_balance_status'              => 'nun',       // حالة رصيد اول المدة -- متزن
            'start_balance'                     => 0,           //  رصيد اول المدة
            'current_balance'                   => 0,           //  الرصيد الفعلي
            'account_number'                    => 10,           //  رقم الحساب للحساب
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,
        ]);

        Supplier::create(
        [
            'name'                              => 'مريم مورد',
            'start_balance_status'              => 'nun',       // حالة رصيد اول المدة -- متزن
            'address'                           => 'طالبية فيصل',
            'start_balance'                     => 0,           //  رصيد اول المدة
            'current_balance'                   => 0,           //  الرصيد الفعلي
            'supplier_code'                     => 1,           //  كود العميل
            'account_number'                    => 10,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
            'supplier_Category_id'              => 1,
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,

        ]);
        //  -- *****************************************



     // B - *****************************************
     Account::create(
        [
            'name'                              => 'هبة مورد',
            'account_type_id'                   => '5',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
            'is_parent'                         => '0',         // هل الحساب اب -- نعم
            'parent_account_number'             => '2',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
            'start_balance_status'              => 'credit',       // حالة رصيد اول المدة -- متزن
            'start_balance'                     => -200,           //  رصيد اول المدة
            'current_balance'                   => -200,           //  الرصيد الفعلي
            'account_number'                    => 11,           //  رقم الحساب للحساب
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,
        ]);
        Supplier::create(
        [
            'name'                              => 'هبة مورد',
            'start_balance_status'              => 'credit',       // حالة رصيد اول المدة -- متزن
            'address'                           => 'اكتوبر',
            'start_balance'                     => -200,           //  رصيد اول المدة
            'current_balance'                   => -200,           //  الرصيد الفعلي
            'supplier_code'                     => 2,           //  كود العميل
            'account_number'                    => 11,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
            'supplier_Category_id'              => 1,
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,

        ]);
        //  -- *****************************************


        // C - *****************************************
         Account::create(
        [
            'name'                              => 'اسراء مورد',
            'account_type_id'                   => '5',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
            'is_parent'                         => '0',         // هل الحساب اب -- نعم
            'parent_account_number'             => '2',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
            'start_balance_status'              => 'debit',       // حالة رصيد اول المدة -- متزن
            'start_balance'                     => 500,           //  رصيد اول المدة
            'current_balance'                   => 500,           //  الرصيد الفعلي
            'account_number'                    => 12,           //  رقم الحساب للحساب
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,
        ]);
        Supplier::create(
        [
            'name'                              => 'اسراء مورد',
            'start_balance_status'              => 'debit',       // حالة رصيد اول المدة -- متزن
            'address'                           => 'اكتوبر',
            'start_balance'                     => 500,           //  رصيد اول المدة
            'current_balance'                   => 500,           //  الرصيد الفعلي
            'supplier_code'                     => 3,           //  كود العميل
            'account_number'                    => 12,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
            'supplier_Category_id'              => 2,
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
                'name'                              => 'عمر مورد',
                'account_type_id'                   => '14',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
                'is_parent'                         => '0',         // هل الحساب اب -- نعم
                'parent_account_number'             => '2',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
                'start_balance_status'              => 'nun',       // حالة رصيد اول المدة -- متزن
                'start_balance'                     => 0,           //  رصيد اول المدة
                'current_balance'                   => 0,           //  الرصيد الفعلي
                'account_number'                    => 12,           //  رقم الحساب للحساب
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,
            ]);

            Supplier::create(
            [
                'name'                              => 'عمر مورد',
                'start_balance_status'              => 'nun',       // حالة رصيد اول المدة -- متزن
                'address'                           => 'طالبية فيصل',
                'start_balance'                     => 0,           //  رصيد اول المدة
                'current_balance'                   => 0,           //  الرصيد الفعلي
                'supplier_code'                     => 1,           //  كود العميل
                'account_number'                    => 12,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
                'supplier_Category_id'              => 3,
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,

            ]);
            //  -- *****************************************



         // B - *****************************************
         Account::create(
            [
                'name'                              => 'مازن مورد',
                'account_type_id'                   => '14',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
                'is_parent'                         => '0',         // هل الحساب اب -- نعم
                'parent_account_number'             => '2',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
                'start_balance_status'              => 'credit',       // حالة رصيد اول المدة -- متزن
                'start_balance'                     => -200,           //  رصيد اول المدة
                'current_balance'                   => -200,           //  الرصيد الفعلي
                'account_number'                    => 13,           //  رقم الحساب للحساب
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,
            ]);
            Supplier::create(
            [
                'name'                              => 'مازن مورد',
                'start_balance_status'              => 'credit',       // حالة رصيد اول المدة -- متزن
                'address'                           => 'اكتوبر',
                'start_balance'                     => -200,           //  رصيد اول المدة
                'current_balance'                   => -200,           //  الرصيد الفعلي
                'supplier_code'                     => 2,           //  كود العميل
                'account_number'                    => 13,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
                'supplier_Category_id'              => 4,
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,

            ]);
            //  -- *****************************************


            // C - *****************************************
             Account::create(
            [
                'name'                              => 'اسامة مورد',
                'account_type_id'                   => '14',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
                'is_parent'                         => '0',         // هل الحساب اب -- نعم
                'parent_account_number'             => '2',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
                'start_balance_status'              => 'debit',       // حالة رصيد اول المدة -- متزن
                'start_balance'                     => 500,           //  رصيد اول المدة
                'current_balance'                   => 500,           //  الرصيد الفعلي
                'account_number'                    => 14,           //  رقم الحساب للحساب
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,
            ]);
            Supplier::create(
            [
                'name'                              => 'اسامة مورد',
                'start_balance_status'              => 'debit',       // حالة رصيد اول المدة -- متزن
                'address'                           => 'اكتوبر',
                'start_balance'                     => 500,           //  رصيد اول المدة
                'current_balance'                   => 500,           //  الرصيد الفعلي
                'supplier_code'                     => 3,           //  كود العميل
                'account_number'                    => 14,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
                'supplier_Category_id'              => 4,
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,

            ]);
            //  -- *****************************************









    }
}
