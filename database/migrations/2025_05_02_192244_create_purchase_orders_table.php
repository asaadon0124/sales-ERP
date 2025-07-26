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
        Schema::create('purchase_orders', function (Blueprint $table)
        {
            $table->id();
            $table->enum('order_type',[0,1,2])->default('0');                   //  نوع الفاتورة 0مشتريات 1 مرتجعات في نفس الفاتورة  2 مرتجعات عموما
            $table->integer('auto_serial');                                     // رقم الفاتورة يتم انشاءه اتوماتيك
            $table->integer('order_number');                                    // رقم الفاتورة يتم ادخالة يدوي من علي فاتورة المشتريات
            $table->date('order_date');                                         // تاريخ الفاتورة
            $table->integer('supplier_code');                                   // اسم المورد الي الفاتورة جاية منه
            $table->enum('approve',[0,1])->default('0');                        //  حالة الفاتورة اذا كان تم اعتمادها و تريحيلها للمخازن ام لا  0 لم تعتمد 1 اعتمدت
            $table->string('notes')->nullable();
            $table->integer('store_id');

            $table->decimal('total_cost_before_all', 10, 2)->default(0.00);     // اجمالي الفاتورة قبل الخصومات و الضرائب
            $table->enum('discount_type',[0,1])->default('0')->nullable();  //  نوع الخصم اذا كان قيمة او نسبة  0 قيمة 1 نسبة
            $table->decimal('discount_percent', 10, 2)->default(0.00)->nullable();     // قيمة نسبة الخصم
            $table->decimal('discount_amount', 10, 2)->default(0.00)->nullable();     // قيمة  الخصم
            $table->decimal('tax_percent', 10, 2)->default(0.00)->nullable();     // نسبة  الضريبة
            $table->decimal('tax_value', 10, 2)->default(0.00)->nullable();     // قيمة  الضريبة
            $table->decimal('total_cost', 10, 2)->default(0.00);     // الاجمالي بعد الخصم و الضرايب
            $table->decimal('mony_for_account', 10, 2)->default(0.00);     // اجمالي الي للمورد او عليه فعلي في الوقت الحالي يحدث باستمرار
            $table->enum('invoice_type',[0,1])->default('0')->nullable();  //  نوع الفاتورة اذا كان كاش او اجل  0 كاش 1 اجل
            $table->decimal('paid', 10, 2)->default(0.00);     // المدفوع من قيمة الفاتورة
            $table->decimal('unpaid', 10, 2)->default(0.00);     // المستحق من قيمة الفاتورة
            $table->integer('treasures_transactions')->nullable();    // حركة الخزنة
            $table->decimal('supplier_balance_before', 10, 2)->default(0.00);    // رصيد المورد قبل الفاتورة
            $table->decimal('supplier_balance_after', 10, 2)->default(0.00);    // رصيد المورد بعد الفاتورة

            $table->integer('company_code');
            $table->integer('account_number');
            $table->integer('created_by');
            $table->integer('approved_by')->nullable();
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
        Schema::dropIfExists('purchase_orders');
    }
};
