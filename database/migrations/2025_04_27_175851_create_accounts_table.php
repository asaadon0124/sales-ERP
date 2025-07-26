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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            $table->integer('account_type_id')->require();
            $table->integer('other_tables_id')->nullable();
            $table->enum('is_parent',['0','1'])->default('0');   // هل لديه حساب اب [ 0 => لا]
            $table->integer('parent_account_number')->nullable();
            $table->integer('account_number')->require();
            $table->enum('start_balance_status',['credit','debit','nun'])->default('credit');   // نوع رصيد اول المدة مدين - دائن - متزن اول المدة
            $table->decimal('start_balance',10,2)->require()->default(0.00);  // الرصيد الحالي
            $table->decimal('current_balance',10,2)->require()->default(0.00);  // الرصيد الحالي
            $table->string('notes')->nullable();

            $table->string('name')->require();
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
        Schema::dropIfExists('accounts');
    }
};
