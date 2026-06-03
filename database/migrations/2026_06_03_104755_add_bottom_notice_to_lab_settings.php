<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBottomNoticeToLabSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lab_settings', function (Blueprint $table) {
            $table->string('bottom_notice')->nullable()->after('footer_note')
                  ->default('Not valid for Court');
            $table->text('bottom_notice_style')->nullable()->after('bottom_notice');
        });
    }

    public function down()
    {
        Schema::table('lab_settings', function (Blueprint $table) {
            $table->dropColumn(['bottom_notice', 'bottom_notice_style']);
        });
    }
}
