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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->integer('supplier_code');
            $table->string('name');
            $table->integer('account_number');
            $table->enum('start_balance_status',['credit','debit','nun'])->default('credit');   // نوع رصيد اول المدة مدين - دائن - متزن اول المدة
            $table->decimal('start_balance',10,2)->require()->default(0.00);  // الرصيد الحالي
            $table->decimal('current_balance',10,2)->require()->default(0.00);  // الرصيد الحالي
            $table->string('notes')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->enum('status',['active','un_active'])->default('active');
            $table->integer('company_code');
            $table->date('date')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('address');
            $table->integer('supplier_Category_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
