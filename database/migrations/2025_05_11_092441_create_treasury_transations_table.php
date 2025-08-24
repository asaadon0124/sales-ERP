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
        Schema::create('treasury_transations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treasury_id')->constrained('treasuries','id')->cascadeOnDelete();                                // اسم الخزنة
            $table->foreignId('moveType_id')->constrained('move_types','id')->cascadeOnDelete();                                // اسم حركة النقدية
            $table->integer('servant_account_id')->nullable();                                                                  // اسم حركة النقدية
            $table->integer('account_id')->nullable();                                                                          // رقم الحساب
            $table->enum('invoice_type_accounts',['purchases','sales','purchase_returns','sales_returns'])->nullable();                                            // العملية تمت علي حساب اي فئة عميل ولا مورد ولا مندوب ولا عام
            $table->enum('invoice_type',[0,1])->default(0);                                                                     // اجل ولا كاش   - 1 == اجل-
            $table->integer('shift_id')->nullable();                                                                            // كود الجدول الاخر المرتبط بالحركة
            $table->enum('cash_source_type',['account','treasury'])->default('account');                                        // هل النقدية المحصلة محصلة من حساب ولا خزنة اخري
            $table->enum('account_type',['suppliers','customers','servants','employee','general'])->default('general');                   // العملية تمت علي حساب اي فئة عميل ولا مورد ولا مندوب ولا عام
            $table->enum('is_approve',['approve','un_approve'])->default('approve');                                            //  حالة الفاتورة اذا كان تم اعتمادها و تريحيلها للمخازن ام لا  0 لم تعتمد 1 اعتمدت
            $table->decimal('cash_amount',10,2)->require()->default(0.00);                                                      // قيمة النقدية المحصلة
            $table->decimal('servant_cash_amount',10,2)->require()->default(0.00);                                                      // قيمة النقدية المحصلة
            $table->decimal('cash_for_account',10,2)->require()->default(0.00);                                                 // خصم او اضافة المبلغ من الحساب المحصل منه او له

            $table->decimal('account_balance_before',10,2)->require()->default(0.00);                                                 // صيد الحساب قبل
            $table->decimal('account_balance_after',10,2)->require()->default(0.00);                                                 // صيد الحساب بعد
            $table->decimal('account_balance_servant_before',10,2)->require()->default(0.00);                                                 // صيد المندوب قبل
            $table->decimal('account_balance_servant_after',10,2)->require()->default(0.00);                                                 // صيد المندوب بعد
            $table->integer('auto_serial');                                                                                     // رقم حركة التحصيل يتم انشاءه اتوماتيك
            $table->integer('isal_number');                                                                                     // رقم حركة التحصيل يتم انشاءه اتوماتيك
            $table->date('move_date')->nullable();                                                                              // تاريخ انشاء الحركة





            // $table->enum('status',['active','un_active'])->default('active');
            $table->integer('company_code');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treasury_transations');
    }
};
