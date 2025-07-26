<?php

namespace Database\Seeders;

use App\Models\ItemCardMovementCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemCardMoveMentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // شركة 1 ***********************************************************
        ItemCardMovementCategory::create(
        [
            'name'              => 'اضافة كمية من الصنف الي المخزن نظير انشاء فاتورة مشتريات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 10001000,
        ]);

        ItemCardMovementCategory::create(
        [
            'name'              => 'صرف كمية من الصنف من المخزن نظير انشاء فاتورة مبيعات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 10001000,
        ]);

        ItemCardMovementCategory::create(
        [
            'name'              => 'اضافة كمية من الصنف في المخزن نظير تعديل و اضافة صنف جديد لفاتورة المشتريات ',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 10001000,
        ]);

        ItemCardMovementCategory::create(
        [
            'name'              => 'صرف كمية من الصنف من المخزن نظير تعديل و اضافة صنف جديد لفاتورة المبيعات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 10001000,
        ]);

        ItemCardMovementCategory::create(
        [
            'name'              => 'اضافة كمية من الصنف في المخزن نظير تعديل و حذف صنف من فاتورة المبيعات ',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 10001000,
        ]);

        ItemCardMovementCategory::create(
        [
            'name'              => 'صرف كمية من الصنف من المخزن نظير تعديل و حذف صنف من فاتورة المشتريات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 10001000,
        ]);

        ItemCardMovementCategory::create(
        [
            'name'              => 'اضافة كمية من الصنف في المخزن نظير مرتجع فاتورة مبيعات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 10001000,
        ]);

        ItemCardMovementCategory::create(
        [
            'name'              => 'صرف كمية من الصنف من المخزن نظير مرتجع فاتورة مشتريات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 10001000,
        ]);



        // شركة 2 ***********************************************************
        ItemCardMovementCategory::create(
        [
            'name'              => 'اضافة كمية من الصنف الي المخزن نظير انشاء فاتورة مشتريات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 20002000,
        ]);

        ItemCardMovementCategory::create(
        [
            'name'              => 'صرف كمية من الصنف من المخزن نظير انشاء فاتورة مبيعات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 20002000,
        ]);

        ItemCardMovementCategory::create(
        [
            'name'              => 'اضافة كمية من الصنف في المخزن نظير تعديل و اضافة صنف جديد لفاتورة المشتريات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 20002000,
        ]);

        ItemCardMovementCategory::create(
        [
            'name'              => 'صرف كمية من الصنف من المخزن نظير تعديل و اضافة صنف جديد لفاتورة المبيعات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 20002000,
        ]);

        ItemCardMovementCategory::create(
        [
            'name'              => 'اضافة كمية من الصنف في المخزن نظير تعديل و حذف صنف من فاتورة المبيعات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 20002000,
        ]);

        ItemCardMovementCategory::create(
        [
            'name'              => 'صرف كمية من الصنف من المخزن نظير تعديل و حذف صنف من فاتورة المشتريات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 20002000,
        ]);

         ItemCardMovementCategory::create(
        [
            'name'              => 'اضافة كمية من الصنف في المخزن نظير مرتجع فاتورة مبيعات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 20002000,
        ]);

        ItemCardMovementCategory::create(
        [
            'name'              => 'صرف كمية من الصنف من المخزن نظير مرتجع فاتورة مشتريات',
            'created_by'        => 2,
            'updated_by'        => 2,
            'company_code'      => 20002000,
        ]);
    }
}
