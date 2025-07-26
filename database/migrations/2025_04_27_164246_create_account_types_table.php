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
        Schema::create('account_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require();
            $table->enum('status',['active','un_active'])->default('active');
            $table->integer('company_code');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->date('date')->nullable();
            $table->enum('related_internal_accounts',['0','1','2'])->default('0');                  // هل تم انشناء الحساب من قبل الادمن او من قبل الموردين او عملاء
                                                                                                    // [الادمن => 0 , الموردين => 1, العملاء => 2]


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_types');
    }
};
