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
        Schema::create('admin_treasuries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins','id')->cascadeOnDelete();
            $table->foreignId('treasury_id')->constrained('treasuries','id')->cascadeOnDelete();
            $table->enum('status',['active','un_active'])->default('active');

            $table->integer('company_code')->nullable();
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
        Schema::dropIfExists('admin_treasuries');
    }
};
