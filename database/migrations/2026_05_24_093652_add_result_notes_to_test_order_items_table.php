<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResultNotesToTestOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_order_items', function (Blueprint $table) {
            $table->text('result_notes')->nullable()->after('result_text');
        });
    }

    public function down()
    {
        Schema::table('test_order_items', function (Blueprint $table) {
            $table->dropColumn('result_notes');
        });
    }
}
