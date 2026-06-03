<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExpandItemKindOnTestOrderItems extends Migration
{
    public function up()
    {
        Schema::table('test_order_items', function (Blueprint $table) {
            $table->string('item_kind', 20)->default('main')->change();
        });
    }

    public function down()
    {
        Schema::table('test_order_items', function (Blueprint $table) {
            $table->string('item_kind', 10)->default('main')->change();
        });
    }
}
