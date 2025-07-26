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
        Schema::create('admin_sittings', function (Blueprint $table) {
            $table->id();
            $table->string('system_name')->unique();
            $table->string('phone');
            $table->string('photo');
            $table->enum('status',['un_active','active']);
            $table->string('general_alert')->nullable();
            $table->string('address');
            $table->integer('company_code')->unique()->nullable();
            $table->integer('customer_parent_account_number')->nullable();   // ده رقم ال aCCOUNT_NUMBER في جدول ال accounts
            $table->integer('supplier_parent_account_number')->nullable();   // ده رقم ال aCCOUNT_NUMBER في جدول ال accounts
            $table->integer('servant_parent_account_number')->nullable();   // ده رقم ال aCCOUNT_NUMBER في جدول ال accounts
            $table->integer('employee_parent_account_number')->nullable();   // ده رقم ال aCCOUNT_NUMBER في جدول ال accounts
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_sittings');
    }
};
