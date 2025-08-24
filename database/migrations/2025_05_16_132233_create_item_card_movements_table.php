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
        Schema::create('item_card_movements', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id');
            $table->integer('item_code');
            $table->integer('item_card_movements_category_id');
            $table->integer('item_card_movements_type_id');
            $table->integer('purchase_order_id')->nullable();
            $table->integer('purchase_orderdetiles__id')->nullable();
            $table->integer('sales_order_id')->nullable();
            $table->integer('item_batch_id');
            $table->integer('sales_orderdetiles__id')->nullable();
            $table->decimal('qty_before_movement', 10, 2)->default(0.00);                             // الكمية قبل الحركة في كل المخازن
            $table->decimal('qty_after_movement', 10, 2)->default(0.00);                             // الكمية بعد الحركة في كل المخازن
            $table->decimal('qty_before_movement_in_store', 10, 2)->default(0.00);                  // الكمية قبل الحركة في المخزن المحدد
            $table->decimal('qty_after_movement_in_store', 10, 2)->default(0.00);                  // الكمية بعد الحركة في المخزن المحدد

            $table->integer('company_code');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->date('date')->nullable();


            $table->string('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_card_movements');
    }
};
