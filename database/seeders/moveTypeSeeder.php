<?php

namespace Database\Seeders;

use App\Models\MoveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class moveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // شركة 1 *********************************************
        // 1 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف بفاتورة خدمات مقدمة لنا',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'global',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 2 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف لرد رأس المال',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'private',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 3 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف مرتب لموظف',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'private',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 4 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف للإيداع البنكي',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'global',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 5 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف نظير مشتريات من مورد',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'global',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 6 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف سلفة علي راتب موظف',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'private',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 7 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف نظير مرتجع مبيعات',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'global',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 8 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف مبلغ لحساب مالي',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'global',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 9 ******************************
        MoveType::create(
            [
                'name'                      => 'تحصيل بفاتورة خدمات نقدمها للغير',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'global',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 10 ******************************
        MoveType::create(
            [
                'name'                      => 'تحصيل خصومات موظفين',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'private',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 11 ******************************
        MoveType::create(
            [
                'name'                      => 'تحصيل نظير مرتجع مشتريات الي مورد',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'global',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 11 ******************************
        MoveType::create(
            [
                'name'                      => 'تحصيل ايراد مبيعات',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'global',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 13 ******************************
        MoveType::create(
            [
                'name'                      => 'تحصيل مبلغ من حساب مالي',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'global',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 14 ******************************
        MoveType::create(
            [
                'name'                      => 'سحب من البنك\r\n',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'global',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 15 ******************************
        MoveType::create(
            [
                'name'                      => 'رد سلفة علي راتب موظف',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'private',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 16 ******************************
        MoveType::create(
            [
                'name'                      => 'مصاريف شراء مثل النولون',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'global',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 17 ******************************
        MoveType::create(
            [
                'name'                      => 'ايراد زيادة راس المال',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'private',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);

        // 18 ******************************
        MoveType::create(
            [
                'name'                      => 'مراجعة واستلام نقدية شفت خزنة مستخدم',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'private',
                'updated_by'                => 1,
                'created_by'                => 1,
                'company_code'              => 10001000,
            ]);







             // شركة 2 *********************************************
        // 1 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف بفاتورة خدمات مقدمة لنا',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'global',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 2 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف لرد رأس المال',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'private',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 3 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف مرتب لموظف',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'private',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 4 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف للإيداع البنكي',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'global',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 5 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف نظير مشتريات من مورد',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'global',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 6 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف سلفة علي راتب موظف',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'private',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 7 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف نظير مرتجع مبيعات',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'global',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 8 ******************************
        MoveType::create(
            [
                'name'                      => 'صرف مبلغ لحساب مالي',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'global',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 9 ******************************
        MoveType::create(
            [
                'name'                      => 'تحصيل بفاتورة خدمات نقدمها للغير',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'global',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 10 ******************************
        MoveType::create(
            [
                'name'                      => 'تحصيل خصومات موظفين',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'private',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 11 ******************************
        MoveType::create(
            [
                'name'                      => 'تحصيل نظير مرتجع مشتريات الي مورد',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'global',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 12 ******************************
        MoveType::create(
            [
                'name'                      => 'تحصيل ايراد مبيعات',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'global',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 13 ******************************
        MoveType::create(
            [
                'name'                      => 'تحصيل مبلغ من حساب مالي',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'global',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 14 ******************************
        MoveType::create(
            [
                'name'                      => 'سحب من البنك\r\n',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'global',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 15 ******************************
        MoveType::create(
            [
                'name'                      => 'رد سلفة علي راتب موظف',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'private',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 16 ******************************
        MoveType::create(
            [
                'name'                      => 'مصاريف شراء مثل النولون',
                'status'                    => 'active',
                'in_screen'                 => 'pay',
                'is_private_internal'       => 'global',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 17 ******************************
        MoveType::create(
            [
                'name'                      => 'ايراد زيادة راس المال',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'private',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);

        // 18 ******************************
        MoveType::create(
            [
                'name'                      => 'مراجعة واستلام نقدية شفت خزنة مستخدم',
                'status'                    => 'active',
                'in_screen'                 => 'collect',
                'is_private_internal'       => 'private',
                'updated_by'                => 2,
                'created_by'                => 2,
                'company_code'              => 20002000,
            ]);
    }
}
