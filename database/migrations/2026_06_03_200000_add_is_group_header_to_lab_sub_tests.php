<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsGroupHeaderToLabSubTests extends Migration
{
    public function up()
    {
        Schema::table('lab_sub_tests', function (Blueprint $table) {
            $table->boolean('is_group_header')->default(false)->after('is_active');
        });
    }

    public function down()
    {
        Schema::table('lab_sub_tests', function (Blueprint $table) {
            $table->dropColumn('is_group_header');
        });
    }
}
