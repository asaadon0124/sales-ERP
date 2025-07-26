<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Servant;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServantSeeder extends Seeder
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
            'name'                              => 'عمر مندوب',
            'account_type_id'                   => '7',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
            'is_parent'                         => '0',         // هل الحساب اب -- نعم
            'parent_account_number'             => '4',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
            'start_balance_status'              => 'nun',       // حالة رصيد اول المدة -- متزن
            'start_balance'                     => 0,           //  رصيد اول المدة
            'current_balance'                   => 0,           //  الرصيد الفعلي
            'account_number'                    => 13,           //  رقم الحساب للحساب
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,
        ]);

        Servant::create(
        [
            'name'                              => 'عمر مندوب',
            'start_balance_status'              => 'nun',       // حالة رصيد اول المدة -- متزن
            'address'                           => 'طالبية فيصل',
            'start_balance'                     => 0,           //  رصيد اول المدة
            'current_balance'                   => 0,           //  الرصيد الفعلي
            'servant_code'                      => 1,           //  كود العميل
            'account_number'                    => 13,           //  رقم الحساب الخاص بالعميل في جدول الحسابا
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,

        ]);
        //  -- *****************************************



     // B - *****************************************
     Account::create(
        [
            'name'                              => 'مسعد مندوب',
            'account_type_id'                   => '7',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
            'is_parent'                         => '0',         // هل الحساب اب -- نعم
            'parent_account_number'             => '4',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
            'start_balance_status'              => 'credit',       // حالة رصيد اول المدة -- متزن
            'start_balance'                     => -200,           //  رصيد اول المدة
            'current_balance'                   => -200,           //  الرصيد الفعلي
            'account_number'                    => 14,           //  رقم الحساب للحساب
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,
        ]);
        Servant::create(
        [
            'name'                              => 'مسعد مندوب',
            'start_balance_status'              => 'credit',       // حالة رصيد اول المدة -- متزن
            'address'                           => 'اكتوبر',
            'start_balance'                     => -200,           //  رصيد اول المدة
            'current_balance'                   => -200,           //  الرصيد الفعلي
            'servant_code'                      => 2,           //  كود العميل
            'account_number'                    => 14,           //  رقم الحساب الخاص بالعميل في جدول الحسابا
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,

        ]);
        //  -- *****************************************


        // C - *****************************************
         Account::create(
        [
            'name'                              => 'ابراهيم مندوب',
            'account_type_id'                   => '7',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
            'is_parent'                         => '0',         // هل الحساب اب -- نعم
            'parent_account_number'             => '4',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
            'start_balance_status'              => 'debit',       // حالة رصيد اول المدة -- متزن
            'start_balance'                     => 500,           //  رصيد اول المدة
            'current_balance'                   => 500,           //  الرصيد الفعلي
            'account_number'                    => 15,           //  رقم الحساب للحساب
            'status'                            => 'active',
            'created_by'                        => 1,
            'updated_by'                        => 1,
            'company_code'                      => 10001000,
        ]);
        Servant::create(
        [
            'name'                              => 'ابراهيم مندوب',
            'start_balance_status'              => 'debit',       // حالة رصيد اول المدة -- متزن
            'address'                           => 'اكتوبر',
            'start_balance'                     => 500,           //  رصيد اول المدة
            'current_balance'                   => 500,           //  الرصيد الفعلي
            'servant_code'                      => 3,           //  كود العميل
            'account_number'                    => 15,           //  رقم الحساب الخاص بالعميل في جدول الحسابا
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
                'name'                              => 'عمرو مندوب',
                'account_type_id'                   => '16',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
                'is_parent'                         => '0',         // هل الحساب اب -- نعم
                'parent_account_number'             => '4',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
                'start_balance_status'              => 'nun',       // حالة رصيد اول المدة -- متزن
                'start_balance'                     => 0,           //  رصيد اول المدة
                'current_balance'                   => 0,           //  الرصيد الفعلي
                'account_number'                    => 15,           //  رقم الحساب للحساب
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,
            ]);

            Servant::create(
            [
                'name'                              => 'عمرو مندوب',
                'start_balance_status'              => 'nun',       // حالة رصيد اول المدة -- متزن
                'address'                           => 'طالبية فيصل',
                'start_balance'                     => 0,           //  رصيد اول المدة
                'current_balance'                   => 0,           //  الرصيد الفعلي
                'servant_code'                      => 1,           //  كود العميل
                'account_number'                    => 15,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,

            ]);
            //  -- *****************************************



         // B - *****************************************
         Account::create(
            [
                'name'                              => 'حمادة مندوب',
                'account_type_id'                   => '16',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
                'is_parent'                         => '0',         // هل الحساب اب -- نعم
                'parent_account_number'             => '4',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
                'start_balance_status'              => 'credit',       // حالة رصيد اول المدة -- متزن
                'start_balance'                     => -200,           //  رصيد اول المدة
                'current_balance'                   => -200,           //  الرصيد الفعلي
                'account_number'                    => 16,           //  رقم الحساب للحساب
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,
            ]);
            Servant::create(
            [
                'name'                              => 'حمادة مندوب',
                'start_balance_status'              => 'credit',       // حالة رصيد اول المدة -- متزن
                'address'                           => 'اكتوبر',
                'start_balance'                     => -200,           //  رصيد اول المدة
                'current_balance'                   => -200,           //  الرصيد الفعلي
                'servant_code'                      => 2,           //  كود العميل
                'account_number'                    => 16,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,

            ]);
            //  -- *****************************************


            // C - *****************************************
             Account::create(
            [
                'name'                              => 'احمد مندوب',
                'account_type_id'                   => '16',         // رقم الحساب العام المسجل في جدول اعدادات الادمن
                'is_parent'                         => '0',         // هل الحساب اب -- نعم
                'parent_account_number'             => '4',         // رقم ال account_number العام المسجل في جدول الadmin_sitting
                'start_balance_status'              => 'debit',       // حالة رصيد اول المدة -- متزن
                'start_balance'                     => 500,           //  رصيد اول المدة
                'current_balance'                   => 500,           //  الرصيد الفعلي
                'account_number'                    => 17,           //  رقم الحساب للحساب
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,
            ]);
            Servant::create(
            [
                'name'                              => 'احمد مندوب',
                'start_balance_status'              => 'debit',       // حالة رصيد اول المدة -- متزن
                'address'                           => 'اكتوبر',
                'start_balance'                     => 500,           //  رصيد اول المدة
                'current_balance'                   => 500,           //  الرصيد الفعلي
                'servant_code'                      => 3,           //  كود العميل
                'account_number'                    => 17,           //  رقم الحساب الخاص بالعميل في جدول الحسابات
                'status'                            => 'active',
                'created_by'                        => 2,
                'updated_by'                        => 2,
                'company_code'                      => 20002000,

            ]);
            //  -- *****************************************









    }
}
