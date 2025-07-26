<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            Item::create(
            [
                'name'                          => 'عليقة بادي',
                'item_type'                     => '1',             // استهلاكي
                'retail_unit'                   => '1',             // الصنف له وحدة فرعية
                'item_category_id'              => '1',             // علف
                'item_unit_id'                  => '1',             // شيكارة الوحدة الرئيسية للقياس
                'sub_item_unit_id'              => '3',             // كيلو الوحدة الفرعية للقياس
                'item_code'                     => 1,               // كود الصنف التلقائي
                'barcode'                       => 1000,            // كود الصنف التلقائي
                'item_wholesale_price'          => 5000,            // سعر البيع الجملة للوحدة الاساسية
                'item_Half_wholesale_price'     => 5500,            // سعر البيع النصف جملة للوحدة الاساسية
                'item_retail_price'             => 6000,            // سعر البيع التجزئة للوحدة الاساسية
                'item_cost_price'               => 4000,            // سعر التكلفة  للوحدة الاساسية

                'sub_item_wholesale_price'      => 5,              // سعر البيع الجملة للوحدة الفرعية
                'sub_item_Half_wholesale_price' => 6,              // سعر البيع النصف جملة للوحدة الفرعية
                'sub_item_retail_price'         => 7,              // سعر البيع التجزئة للوحدة الفرعية
                'sub_item_cost_price'           => 4,              // سعر التكلفة  للوحدة الفرعية
                'qty_sub_item_unit'             => 1000,             // كية الوحدات الفرعية داخل   الوحدة الاساسية
                'is_change'                     => '0',             // سعر الصنف غير قابل للنعديل في فاتورة المبيعات
                'status'                        => 'active',
                'created_by'                    => 1,
                'updated_by'                    => 1,
                'company_code'                  => 10001000,
            ]);


            Item::create(
            [
                'name'                          => 'عليقة نامي',
                'item_type'                     => '1',             // استهلاكي
                'retail_unit'                   => '1',             // الصنف له وحدة فرعية
                'item_category_id'              => '1',             // علف
                'item_unit_id'                  => '1',             // شيكارة الوحدة الرئيسية للقياس
                'sub_item_unit_id'              => '3',             // كيلو الوحدة الفرعية للقياس
                'item_code'                     => 2,               // كود الصنف التلقائي
                'barcode'                       => 1111,            // كود الصنف التلقائي
                'item_wholesale_price'          => 5500,            // سعر البيع الجملة للوحدة الاساسية
                'item_Half_wholesale_price'     => 6000,            // سعر البيع النصف جملة للوحدة الاساسية
                'item_retail_price'             => 6500,            // سعر البيع التجزئة للوحدة الاساسية
                'item_cost_price'               => 4500,            // سعر التكلفة  للوحدة الاساسية

                'sub_item_wholesale_price'      => 6,              // سعر البيع الجملة للوحدة الفرعية
                'sub_item_Half_wholesale_price' => 8,              // سعر البيع النصف جملة للوحدة الفرعية
                'sub_item_retail_price'         => 10,              // سعر البيع التجزئة للوحدة الفرعية
                'sub_item_cost_price'           => 4.5,              // سعر التكلفة  للوحدة الفرعية
                'qty_sub_item_unit'             => 1000,             // كية الوحدات الفرعية داخل   الوحدة الاساسية
                'is_change'                     => '1',             // سعر الصنف غير قابل للنعديل في فاتورة المبيعات
                'status'                        => 'active',
                'created_by'                    => 1,
                'updated_by'                    => 1,
                'company_code'                  => 10001000,
            ]);


            Item::create(
            [
                'name'                          => 'عليقة ناهي',
                'item_type'                     => '1',             // استهلاكي
                'retail_unit'                   => '1',             // الصنف له وحدة فرعية
                'item_category_id'              => '1',             // علف
                'item_unit_id'                  => '1',             // شيكارة الوحدة الرئيسية للقياس
                'sub_item_unit_id'              => '3',             // كيلو الوحدة الفرعية للقياس
                'item_code'                     => 3,               // كود الصنف التلقائي
                'barcode'                       => 1222,            // كود الصنف التلقائي
                'item_wholesale_price'          => 6000,            // سعر البيع الجملة للوحدة الاساسية
                'item_Half_wholesale_price'     => 6500,            // سعر البيع النصف جملة للوحدة الاساسية
                'item_retail_price'             => 7000,            // سعر البيع التجزئة للوحدة الاساسية
                'item_cost_price'               => 5000,            // سعر التكلفة  للوحدة الاساسية

                'sub_item_wholesale_price'      => 7,              // سعر البيع الجملة للوحدة الفرعية
                'sub_item_Half_wholesale_price' => 8,              // سعر البيع النصف جملة للوحدة الفرعية
                'sub_item_retail_price'         => 10,              // سعر البيع التجزئة للوحدة الفرعية
                'sub_item_cost_price'           => 5,              // سعر التكلفة  للوحدة الفرعية
                'qty_sub_item_unit'             => 1000,             // كية الوحدات الفرعية داخل   الوحدة الاساسية
                'is_change'                     => '1',             // سعر الصنف غير قابل للنعديل في فاتورة المبيعات
                'status'                        => 'active',
                'created_by'                    => 1,
                'updated_by'                    => 1,
                'company_code'                  => 10001000,
            ]);


            Item::create(
            [
                'name'                          => 'سماد للارض ',
                'item_type'                     => '0',             // مخزني
                'retail_unit'                   => '0',             // الصنف ليس له وحدة فرعية
                'item_category_id'              => '2',             // سماد
                'item_unit_id'                  => '1',             // شيكارة الوحدة الرئيسية للقياس
                'item_code'                     => 4,               // كود الصنف التلقائي
                'barcode'                       => 1333,            // كود الصنف التلقائي
                'item_wholesale_price'          => 1000,            // سعر البيع الجملة للوحدة الاساسية
                'item_Half_wholesale_price'     => 1500,            // سعر البيع النصف جملة للوحدة الاساسية
                'item_retail_price'             => 2000,            // سعر البيع التجزئة للوحدة الاساسية
                'item_cost_price'               => 500,            // سعر التكلفة  للوحدة الاساسية
                'is_change'                     => '1',             // سعر الصنف غير قابل للنعديل في فاتورة المبيعات
                'status'                        => 'active',
                'created_by'                    => 1,
                'updated_by'                    => 1,
                'company_code'                  => 10001000,
            ]);


            Item::create(
            [
                'name'                          => 'سماد للزراعة ',
                'item_type'                     => '0',             // مخزني
                'retail_unit'                   => '0',             // الصنف ليس له وحدة فرعية
                'item_category_id'              => '2',             // سماد
                'item_unit_id'                  => '1',             // شيكارة الوحدة الرئيسية للقياس
                'item_code'                     => 5,               // كود الصنف التلقائي
                'barcode'                       => 1444,            // كود الصنف التلقائي
                'item_wholesale_price'          => 3000,            // سعر البيع الجملة للوحدة الاساسية
                'item_Half_wholesale_price'     => 3500,            // سعر البيع النصف جملة للوحدة الاساسية
                'item_retail_price'             => 4000,            // سعر البيع التجزئة للوحدة الاساسية
                'item_cost_price'               => 2500,            // سعر التكلفة  للوحدة الاساسية
                'is_change'                     => '0',             // سعر الصنف غير قابل للنعديل في فاتورة المبيعات
                'status'                        => 'active',
                'created_by'                    => 1,
                'updated_by'                    => 1,
                'company_code'                  => 10001000,
            ]);






            Item::create(
                [
                    'name'                          => 'فول',
                    'item_type'                     => '1',             // استهلاكي
                    'retail_unit'                   => '1',             // الصنف له وحدة فرعية
                    'item_category_id'              => '3',             // بقوليات
                    'item_unit_id'                  => '4',             // كرتونة الوحدة الرئيسية للقياس
                    'sub_item_unit_id'              => '5',             // كيلو الوحدة الفرعية للقياس
                    'item_code'                     => 1,               // كود الصنف التلقائي
                    'barcode'                       => 1000,            // كود الصنف التلقائي
                    'item_wholesale_price'          => 1500,            // سعر البيع الجملة للوحدة الاساسية
                    'item_Half_wholesale_price'     => 2000,            // سعر البيع النصف جملة للوحدة الاساسية
                    'item_retail_price'             => 2500,            // سعر البيع التجزئة للوحدة الاساسية
                    'item_cost_price'               => 1000,            // سعر التكلفة  للوحدة الاساسية

                    'sub_item_wholesale_price'      => 20,              // سعر البيع الجملة للوحدة الفرعية
                    'sub_item_Half_wholesale_price' => 25,              // سعر البيع النصف جملة للوحدة الفرعية
                    'sub_item_retail_price'         => 30,              // سعر البيع التجزئة للوحدة الفرعية
                    'sub_item_cost_price'           => 10,              // سعر التكلفة  للوحدة الفرعية
                    'qty_sub_item_unit'             => 100,             // كية الوحدات الفرعية داخل   الوحدة الاساسية
                    'is_change'                     => '1',             // سعر الصنف  قابل للنعديل في فاتورة المبيعات
                    'status'                        => 'active',
                    'created_by'                    => 2,
                    'updated_by'                    => 2,
                    'company_code'                  => 20002000,
                ]);


            Item::create(
                [
                    'name'                          => 'عدس',
                    'item_type'                     => '1',             // استهلاكي
                    'retail_unit'                   => '1',             // الصنف له وحدة فرعية
                    'item_category_id'              => '3',             // بقوليات
                    'item_unit_id'                  => '4',             // كرتونة الوحدة الرئيسية للقياس
                    'sub_item_unit_id'              => '5',             // كيلو الوحدة الفرعية للقياس
                    'item_code'                     => 2,               // كود الصنف التلقائي
                    'barcode'                       => 2000,            // كود الصنف التلقائي
                    'item_wholesale_price'          => 3000,            // سعر البيع الجملة للوحدة الاساسية
                    'item_Half_wholesale_price'     => 3500,            // سعر البيع النصف جملة للوحدة الاساسية
                    'item_retail_price'             => 4000,            // سعر البيع التجزئة للوحدة الاساسية
                    'item_cost_price'               => 1500,            // سعر التكلفة  للوحدة الاساسية

                    'sub_item_wholesale_price'      => 20,              // سعر البيع الجملة للوحدة الفرعية
                    'sub_item_Half_wholesale_price' => 25,              // سعر البيع النصف جملة للوحدة الفرعية
                    'sub_item_retail_price'         => 30,              // سعر البيع التجزئة للوحدة الفرعية
                    'sub_item_cost_price'           => 15,              // سعر التكلفة  للوحدة الفرعية
                    'qty_sub_item_unit'             => 100,             // كية الوحدات الفرعية داخل   الوحدة الاساسية
                    'is_change'                     => '1',             // سعر الصنف  قابل للنعديل في فاتورة المبيعات
                    'status'                        => 'active',
                    'created_by'                    => 2,
                    'updated_by'                    => 2,
                    'company_code'                  => 20002000,
                ]);


            Item::create(
                [
                    'name'                          => 'زباد',
                    'item_type'                     => '1',             // استهلاكي
                    'retail_unit'                   => '1',             // الصنف له وحدة فرعية
                    'item_category_id'              => '4',             // مواد غزائية
                    'item_unit_id'                  => '4',             // كرتونة الوحدة الرئيسية للقياس
                    'sub_item_unit_id'              => '5',             // كيلو الوحدة الفرعية للقياس
                    'item_code'                     => 3,               // كود الصنف التلقائي
                    'barcode'                       => 200,            // كود الصنف التلقائي
                    'item_wholesale_price'          => 300,            // سعر البيع الجملة للوحدة الاساسية
                    'item_Half_wholesale_price'     => 350,            // سعر البيع النصف جملة للوحدة الاساسية
                    'item_retail_price'             => 400,            // سعر البيع التجزئة للوحدة الاساسية
                    'item_cost_price'               => 150,            // سعر التكلفة  للوحدة الاساسية

                    'sub_item_wholesale_price'      => 15,              // سعر البيع الجملة للوحدة الفرعية
                    'sub_item_Half_wholesale_price' => 20,              // سعر البيع النصف جملة للوحدة الفرعية
                    'sub_item_retail_price'         => 25,              // سعر البيع التجزئة للوحدة الفرعية
                    'sub_item_cost_price'           => 20,              // سعر التكلفة  للوحدة الفرعية
                    'qty_sub_item_unit'             => 10,             // كية الوحدات الفرعية داخل   الوحدة الاساسية
                    'is_change'                     => '1',             // سعر الصنف  قابل للنعديل في فاتورة المبيعات
                    'status'                        => 'active',
                    'created_by'                    => 2,
                    'updated_by'                    => 2,
                    'company_code'                  => 20002000,
                ]);



                 Item::create(
                [
                    'name'                          => 'سمسم',
                    'item_type'                     => '0',             // استهلاكي
                    'retail_unit'                   => '1',             // الصنف له وحدة فرعية
                    'item_category_id'              => '3',             // بقوليات
                    'item_unit_id'                  => '4',             // كرتونة الوحدة الرئيسية للقياس
                    'sub_item_unit_id'              => '5',             // كيلو الوحدة الفرعية للقياس
                    'item_code'                     => 4,               // كود الصنف التلقائي
                    'barcode'                       => 3500,            // كود الصنف التلقائي
                    'item_wholesale_price'          => 5000,            // سعر البيع الجملة للوحدة الاساسية
                    'item_Half_wholesale_price'     => 5500,            // سعر البيع النصف جملة للوحدة الاساسية
                    'item_retail_price'             => 6000,            // سعر البيع التجزئة للوحدة الاساسية
                    'item_cost_price'               => 4000,            // سعر التكلفة  للوحدة الاساسية

                    'sub_item_wholesale_price'      => 45,              // سعر البيع الجملة للوحدة الفرعية
                    'sub_item_Half_wholesale_price' => 60,              // سعر البيع النصف جملة للوحدة الفرعية
                    'sub_item_retail_price'         => 65,              // سعر البيع التجزئة للوحدة الفرعية
                    'sub_item_cost_price'           => 40,              // سعر التكلفة  للوحدة الفرعية
                    'qty_sub_item_unit'             => 100,             // كية الوحدات الفرعية داخل   الوحدة الاساسية
                    'is_change'                     => '1',             // سعر الصنف  قابل للنعديل في فاتورة المبيعات
                    'status'                        => 'active',
                    'created_by'                    => 2,
                    'updated_by'                    => 2,
                    'company_code'                  => 20002000,
                ]);

    }
}
