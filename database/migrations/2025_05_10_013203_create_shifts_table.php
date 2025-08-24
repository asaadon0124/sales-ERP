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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admin_id')->constrained('admins','id')->cascadeOnDelete();                                           // اسم المستخدم للخزنة
            $table->foreignId('treasury_id')->constrained('treasuries','id')->cascadeOnDelete();                                    // اسم الخزنة
            $table->decimal('start_balance',10,2)->require()->default(0.00);                                                        // الرصيد اول الشيفت
            $table->dateTime('start_date')->nullable();                                                                             // تاريخ بداية الشيفت
            $table->dateTime('end_date')->nullable();                                                                               // تاريخ نهاية الشيفت
            $table->enum('shift_status',['active','un_active'])->default('active');                                                 // هل الشيفت شغال او منتهي مع العلم انه لو شغال لا يستطيع اي مستخدم اخر العمل عليه
            $table->enum('is_delevered_review',['yes','no'])->default('no');                                                        // هل تم تسليم و مراجعة النقدية قبل الشخص المسموح له بذالك
            $table->foreignId('delevered_to_admin_id')->nullable()->constrained('admins','id')->cascadeOnDelete();                  //  اسم المستخدم الي استلم مني الشيفت وراجعه
            $table->foreignId('delevered_to_shift_id')->nullable();         // اسم الشيفت الذي تسلم هذا الشيفت وراجعه
            $table->foreignId('delevered_to_treasury_id')->nullable()->constrained('treasuries','id')->cascadeOnDelete();           // اسم الخزنة الذي تسلمت هذا الشيفت وراجعه
            $table->decimal('cash_should_delevered',10,2)->require()->default(0.00);                                                // النقدية الواجب تسليمها في نهاية الشيفت
            $table->decimal('cash_actually_delivered',10,2)->require()->default(0.00);                                              // النقدية المسلمة بالفعل في نهاية الشيفت  ---- النقدية الي مع المستخدم فعلا
            $table->enum('cash_status',['nun','plus','mins'])->default('nun');                                                      // حالة النقدية المسلمة بالفعل     متزن -زيادة -عجز
            $table->decimal('cash_status_value',10,2)->require()->default(0.00);                                                    // قيمة العجز او الزيادة
            $table->enum('recive_type',['same','anther'])->default('anther');                                                       // تسليم الشبفت و النقدية علي نفس الخزنة او خزنة اخري
            $table->dateTime('Review_recive_date')->nullable();                                                                     // تاريخ المراجعة
            $table->integer('treasuries_transaction_id')->nullable();                                                               // رقم ايصال النقدية
            $table->integer('auto_serial');                                                                                         // رقم الشيفت يتم انشاءه اتوماتيك



            $table->string('notes')->nullable();
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
        Schema::dropIfExists('shifts');
    }
};
