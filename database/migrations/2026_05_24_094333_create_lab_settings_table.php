<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_settings', function (Blueprint $table) {
            $table->id();
            $table->string('lab_name')->default('Al Ghani Lab');
            $table->text('lab_address')->nullable();
            $table->string('lab_email')->nullable();
            $table->string('lab_phone')->nullable();
            $table->string('logo')->nullable();       // stored filename under public/letterheads/
            $table->string('footer_note')->default('Electronically generated report — No need of signature');
            $table->json('doctors')->nullable();      // [{name, description}]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lab_settings');
    }
}
