<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            // One invoice per order
            $table->foreignId('test_order_id')->constrained('test_orders')->cascadeOnDelete();
            $table->unique('test_order_id');

            // Billing numbers
            $table->decimal('subtotal', 10, 2)->default(0);

            // Discount selection
            $table->enum('discount_type', ['none', 'percent', 'flat'])->default('none');
            $table->decimal('discount_value', 10, 2)->default(0);   // percent (e.g., 10) OR flat (e.g., 500)
            $table->decimal('discount_amount', 10, 2)->default(0);  // calculated amount stored for record

            $table->decimal('total_amount', 10, 2)->default(0);

            // Payments tracking
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');

            // Optional invoice number (later can auto-generate)
            $table->string('invoice_no')->unique()->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
