<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_order_items', function (Blueprint $table) {

         $table->id();

        $table->foreignId('test_order_id')
            ->constrained('test_orders')
            ->cascadeOnDelete();

        // Billing source
        $table->foreignId('test_type_id')
            ->constrained('test_types')
            ->restrictOnDelete();

        // Price snapshot of the type (CBC=1500 stored here)
        $table->decimal('type_price_snapshot', 10, 2)->default(0);

        // Either main test OR sub test (independent rows)
        $table->foreignId('lab_test_id')->nullable()
            ->constrained('lab_tests')
            ->nullOnDelete();

        $table->foreignId('lab_sub_test_id')->nullable()
            ->constrained('lab_sub_tests')
            ->nullOnDelete();

        $table->foreignId('test_category_id')->nullable()
            ->constrained('test_categories')
            ->nullOnDelete();

        $table->foreignId('assigned_by_user_id')->nullable()
            ->constrained('users')
            ->nullOnDelete();

        // For UI only: both act like tests (results, ranges, units)
        // IMPORTANT: use string not enum to avoid truncation issues
        $table->string('item_kind', 10)->default('main'); // main | sub

        // snapshots
        $table->string('test_name_snapshot');
        $table->string('test_code_snapshot')->nullable();
        $table->string('unit_snapshot')->nullable();
        $table->string('reference_range_snapshot')->nullable();
        $table->unsignedInteger('sort_order_snapshot')->default(0);

        // results
        $table->enum('result_status', ['pending', 'processing', 'ready'])->default('pending');
        $table->text('result_text')->nullable();
        $table->string('result_file')->nullable();
        $table->timestamp('result_posted_at')->nullable();

        $table->foreignId('result_posted_by_user_id')->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamps();

        // prevent duplicates per order
        $table->unique(['test_order_id', 'lab_test_id'], 'u_order_main_test');
        $table->unique(['test_order_id', 'lab_sub_test_id'], 'u_order_sub_test');

        $table->index(['test_order_id', 'test_type_id']);
        $table->index('result_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_order_items');
    }
}
