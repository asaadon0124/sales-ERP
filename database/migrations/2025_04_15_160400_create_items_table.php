<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    //  1 -Item_type =>
    //  [
    //     '0'      => 'مخزني',
    //     '1'      => 'استهلاكي',
    //     '2'      => 'عهدة'
    // ]
    // 2 - parent_item_id => الصنف الرئيسي لهذا الصنف
    // 3 - retail_unit => هل للصنف وحدة تجزئة
    // [
    //     0 => 'no',
    //     1 => 'yes'
    // ]
    // 4 - qty_sub_item_unit => الكمية الي موجودة في وحدة التجزئة بالنسبة للوحدة الاساسية ( الشيكارة بها 100 كيلو )

    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require();
            $table->enum('item_type',[0,1,2])->default('0');                            // نوع الصنف -- مخزني - استهلاكي - عهدة
            $table->enum('retail_unit',[0,1])->default('0');                            // هل للصنف وحدة فرعية ام لا
            $table->integer('item_category_id');                                        // فئة الصنف او القسم الرئيسي للصنف
            $table->integer('item_unit_id')->nullable();                                // وحدة الصنف  الاساسية
            $table->integer('sub_item_unit_id')->nullable();                            // وحدة الصنف  الفرعية
            $table->integer('parent_item_id')->nullable();
            $table->integer('qty_sub_item_unit')->default(0);                           // كمية الوحدات الجزئية داخل الوحدة الاساسية -- طن به 1000 كجرام
            $table->integer('item_code')->require();
            $table->string('barcode')->require();

            $table->decimal('item_wholesale_price',10, 2)->default(0.00);               // سعر الجملة للوحدة الاساسية
            $table->decimal('item_Half_wholesale_price',10, 2)->default(0.00);          // سعر النصف جملة للوحدة الاساسية
            $table->decimal('item_retail_price',10, 2)->default(0.00);                  // سعر التجزئة للوحدة الاساسية
            $table->decimal('item_cost_price',10, 2)->default(0.00);                    // سعر التكلفة للوحدة الاساسية

            $table->decimal('sub_item_wholesale_price',10, 2)->default(0.00);           // سعر الجملة للوحدة الفرعية
            $table->decimal('sub_item_Half_wholesale_price',10, 2)->default(0.00);      // سعر النصف جملة للوحدة الفرعية
            $table->decimal('sub_item_retail_price',10, 2)->default(0.00);              // سعر التجزئة للوحدة الفرعية
            $table->decimal('sub_item_cost_price',10, 2)->default(0.00);                // سعر التكلفة للوحدة الفرعية


            $table->decimal('total_qty_for_parent',10, 2)->default(0.00);                // الكمية الكلية للصنف بالوحدة الاساسية
            $table->decimal('sub_item_qty',10, 2)->default(0.00);                       // الكمية الفرعية المتبيقية بعد حساب الكمية الاساسية للصنف
            $table->decimal('total_qty_for_sub_items',10, 2)->default(0.00);            // الكمية الكلية للصنف بالوحدة الفرعية


            $table->enum('is_change',[0,1])->default('0');                              // هل سعر الصنف ثابت عند البيع ولا متغير 0=> ثابت



            $table->enum('status',['active','un_active'])->default('active');
            $table->integer('company_code');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->date('date')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
