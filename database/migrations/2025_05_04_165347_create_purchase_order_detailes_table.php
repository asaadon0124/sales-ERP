<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_order_detailes', function (Blueprint $table) {
            $table->id();
            $table->enum('order_type',[0,1,2])->default('0');                           //  نوع الفاتورة 0مشتريات 1 مرتجعات في نفس الفاتورة  2 مرتجعات عموما
            $table->integer('auto_serial_purchase_orders');                             // رقم الفاتورة يتم انشاءه اتوماتيك الخاص بجدول الاوردر
            $table->integer('company_code');

            $table->integer('item_code');                                               // اسم الصنف بالكود
            $table->integer('item_units_id');                                           // اسم الوحدة المستبم بها الكمية
            $table->integer('batch_id')->nullable();                                    // الصنف تبع اي باتشة
            $table->enum('is_master',['master','sub_master'])->default('master');       // هل الوحدة دي اساسية ولا فرعية
            $table->enum('item_type',[0,1,2]);                                          // نوع الصنف -- مخزني - استهلاكي - عهدة


            $table->decimal('qty', 10, 2)->default(0.00);                             // الكمية
            $table->decimal('unit_price', 10, 2)->default(0.00);                      // سعر الوحدة
            $table->decimal('total', 10, 2)->default(0.00);                          // الاجمالي

            $table->date('order_date');                                             // تاريخ الفاتورة
            $table->date('production_date')->nullable();                                         // تاريخ الانتاج
            $table->date('expire_date')->nullable();                                // تاريخ الانتهاء
            $table->integer('created_by');
            $table->integer('updated_by');




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_detailes');
    }
};
