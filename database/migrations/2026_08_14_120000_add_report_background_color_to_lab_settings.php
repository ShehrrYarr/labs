<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReportBackgroundColorToLabSettings extends Migration
{
    public function up()
    {
        Schema::table('lab_settings', function (Blueprint $table) {
            $table->string('report_background_color', 20)->nullable()->default('#dce9f7');
        });
    }

    public function down()
    {
        Schema::table('lab_settings', function (Blueprint $table) {
            $table->dropColumn('report_background_color');
        });
    }
}
