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
        Schema::create('item_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores','id')->cascadeOnDelete();                   // اسم المخزن
            $table->foreignId('item_unit_id')->constrained('item_units','id')->cascadeOnDelete();           // اسم وحدة الصنف
            $table->integer('item_code')->require();                                                        // كود الصنف الالي  هذا الحقل بيربط مع جدول الاصناف
            $table->decimal('item_cost_price',10, 2)->default(0.00);                                        // سعر التكلفة للوحدة
            $table->decimal('qty', 10, 2)->default(0.00);                                                   // الكمية بالوحدة الاب
            $table->decimal('deduction', 10, 2)->default(0.00);                                             // كمية الخصم عند البيع
            $table->decimal('total', 10, 2)->default(0.00);                                                 // اجمالي سعر شراء الباتش
            $table->date('production_date')->nullable();                                                    // تاريخ الانتاج
            $table->date('expire_date')->nullable();                                                        // تاريخ الانتهاء



            $table->integer('auto_serial');                                                                 // رقم الباتش يتم انشاءه اتوماتيك
            $table->enum('status',['active','un_active'])->default('active');
            $table->integer('company_code');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_batches');
    }
};
