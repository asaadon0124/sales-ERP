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
        Schema::create('sales_order_details', function (Blueprint $table)
        {
            $table->id();
            $table->enum('sales_item_type_detailes',[0,1,2])->default('0');                      //  نوع البيع [ 0 => قطاعي , 1 => نصف جملة , 2 => جملة ]
            $table->enum('item_type',[0,1,2]);                                          // نوع الصنف -- مخزني - استهلاكي - عهدة

            $table->integer('auto_serial_sales_order');                             // رقم الفاتورة يتم انشاءه اتوماتيك الخاص بجدول الاوردر
            $table->integer('item_code');                                               // اسم الصنف بالكود
            $table->integer('item_units_id');                                           // اسم الوحدة المستبم بها الكمية
            $table->integer('batch_id')->nullable();
            $table->integer('store_id')->nullable();

            $table->enum('is_master',['master','sub_master'])->default('master');       // هل الوحدة دي اساسية ولا فرعية
            $table->enum('is_bouns',['yes','no'])->default('no');                       // هل الصنف بونص او لا  لو بونص حيبقي سعره 0

            $table->decimal('qty', 10, 4)->default(0.00);                             // الكمية
            $table->decimal('unit_price', 10, 2)->default(0.00);                      // سعر الوحدة
            $table->decimal('total', 10, 2)->default(0.00);                          // الاجمالي

            $table->date('order_date');                                             // تاريخ الفاتورة
            $table->date('production_date')->nullable();                            // تاريخ الانتاج
            $table->date('expire_date')->nullable();                                // تاريخ الانتهاء
            $table->integer('company_code');
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
        Schema::dropIfExists('sales_order_details');
    }
};
