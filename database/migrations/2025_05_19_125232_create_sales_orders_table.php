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
        Schema::create('sales_orders', function (Blueprint $table)
        {
            $table->id();
            $table->enum('order_type',[0,1,2])->default('0');                   //  نوع الفاتورة مبيعات 1 مرتجعات في نفس الفاتورة  2 مرتجعات عموما
            $table->integer('auto_serial');                                     // رقم الفاتورة يتم انشاءه اتوماتيك
            $table->integer('auto_serial_servant_invoice')->nullable();                     // عدد الفواتر الي المندوب عملها يتم انشاءه اتوماتيك

            $table->date('order_date');                                         // تاريخ الفاتورة
            $table->integer('customer_code');                                   // اسم العميل الي الفاتورة جاية منه
            $table->integer('customer_account_number');                         // رقم  حساب العميل في جدول الحسابات
            $table->integer('servant_code')->nullable();                                    // اسم المندوب الي الفاتورة جاية منه
            $table->integer('matrial_types_id');                                // فئة الفاتورة
            $table->integer('treasures_transactions_id')->nullable();           // حركة الخزنة
            $table->integer('treasures_transactions__servant_id')->nullable();           // حركة الخزنة
            $table->enum('approve',[0,1])->default('0');                        //  حالة الفاتورة اذا كان تم اعتمادها و تريحيلها للمخازن ام لا  0 لم تعتمد 1 اعتمدت
            $table->enum('is_fixed_customer',[0,1])->default('0');              //  هل العميل صاحب الفاتورة ثابت ولا عميل طياري (0 عميل ثابت 1 عميل طياري
            $table->enum('sales_item_type',[0,1,2])->default('0');              //  نوع البيع [ 0 => قطاعي , 1 => نصف جملة , 2 => جملة ]
            $table->enum('items_type',[0,1])->default('0');                     //  نوع الاصناف داخل الفاتورة ثابت ولا متغير يعني في اصناف جملة و اصناف نص جمة ولا كلها نوع واحد [ 0 => ثابت , 1 => متغير ]



            $table->decimal('total_cost_before_all', 10, 2)->default(0.00);                     // اجمالي الفاتورة قبل الخصومات و الضرائب
            $table->enum('discount_type',[0,1])->default('0')->nullable();                      //  نوع الخصم اذا كان قيمة او نسبة  0 قيمة 1 نسبة
            $table->decimal('discount_percent', 10, 2)->default(0.00)->nullable();              // قيمة نسبة الخصم
            $table->decimal('discount_amount', 10, 2)->default(0.00)->nullable();               // قيمة  الخصم
            $table->decimal('total_before_discount', 10, 2)->default(0.00);                   // اجمالي الفاتورة قبل الخصومات و الضرائب
            $table->decimal('tax_percent', 10, 2)->default(0.00)->nullable();                   // نسبة  الضريبة
            $table->decimal('tax_value', 10, 2)->default(0.00)->nullable();                     // قيمة  الضريبة
            $table->decimal('total_cost', 10, 2)->default(0.00);                                // الاجمالي بعد الخصم و الضرايب

            $table->decimal('mony_for_account', 10, 2)->default(0.00);                          // اجمالي الي للعميل او عليه فعلي في الوقت الحالي يحدث باستمرار
            $table->enum('invoice_type',[0,1])->default('0')->nullable();                       //  نوع الفاتورة اذا كان كاش او اجل  0 كاش 1 اجل
            $table->decimal('paid', 10, 2)->default(0.00);                                      // المدفوع من قيمة الفاتورة
            $table->decimal('unpaid', 10, 2)->default(0.00);                                    // المستحق من قيمة الفاتورة

            $table->decimal('servant_commission_percent_type', 10, 2)->default(0.00)->nullable();// نوع العمولة الخاصة بالمندوب
            $table->decimal('servant_commission_percent', 10, 2)->default(0.00)->nullable();     // نسبة العمولة الخاصة بالمندوب
            $table->decimal('servant_commission_amount', 10, 2)->default(0.00)->nullable();     // قيمة العمولة الخاصة بالمندوب

             $table->decimal('customer_balance_before', 10, 2)->default(0.00);                  // رصيد العميل قبل الفاتورة
            $table->decimal('customer_balance_after', 10, 2)->default(0.00);                    // رصيد العميل بعد الفاتورة

            $table->string('notes')->nullable();
            $table->integer('company_code');
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
        Schema::dropIfExists('sales_orders');
    }
};
