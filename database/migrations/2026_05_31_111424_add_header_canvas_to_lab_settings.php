<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeaderCanvasToLabSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lab_settings', function (Blueprint $table) {
            $table->json('header_images')->nullable()->after('logo');
            $table->text('header_canvas_json')->nullable()->after('header_images');
        });
    }

    public function down()
    {
        Schema::table('lab_settings', function (Blueprint $table) {
            $table->dropColumn(['header_images', 'header_canvas_json']);
        });
    }
}
