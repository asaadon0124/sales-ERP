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
        Schema::create('item_card_movement_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require();
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
        Schema::dropIfExists('item_card_movement_categories');
    }
};
