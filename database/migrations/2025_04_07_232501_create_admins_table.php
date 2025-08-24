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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('company_code')->nullable();

            $table->integer('employee_code');
            $table->integer('account_number');
            $table->enum('start_balance_status',['credit','debit','nun'])->default('credit');   // نوع رصيد اول المدة مدين - دائن - متزن اول المدة
            $table->decimal('start_balance',10,2)->require()->default(0.00);  // الرصيد الحالي
            $table->decimal('current_balance',10,2)->require()->default(0.00);  // الرصيد الحالي
            $table->string('notes')->nullable();
            // $table->integer('customer_parent_account_number ')->nullable();


            $table->enum('status',['active','un_active'])->default('active');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
