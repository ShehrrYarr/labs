<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterReferenceRangeSnapshotOnTestOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_order_items', function (Blueprint $table) {
            $table->longText('reference_range_snapshot')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('test_order_items', function (Blueprint $table) {
           $table->string('reference_range_snapshot', 255)->nullable()->change();
        });
    }
}
