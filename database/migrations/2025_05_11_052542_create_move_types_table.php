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
        Schema::create('move_types', function (Blueprint $table) {
            $table->id();

            $table->string('name')->require();
            $table->enum('status',['active','un_active'])->default('active');
            $table->enum('in_screen',['pay','collect'])->default('pay');
            $table->enum('is_private_internal',['global','private'])->default('global');        // الحركات الداخلية و الخارجية
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
        Schema::dropIfExists('move_types');
    }
};
