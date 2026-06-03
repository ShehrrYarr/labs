<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWatermarkCanvasToLabSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lab_settings', function (Blueprint $table) {
            $table->text('watermark_canvas_json')->nullable()->after('header_canvas_json');
        });
    }

    public function down()
    {
        Schema::table('lab_settings', function (Blueprint $table) {
            $table->dropColumn('watermark_canvas_json');
        });
    }
}
