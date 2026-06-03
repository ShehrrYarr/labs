<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubtitleToTestCategories extends Migration
{
    public function up()
    {
        Schema::table('test_categories', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('test_categories', function (Blueprint $table) {
            $table->dropColumn('subtitle');
        });
    }
}
