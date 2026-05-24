<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameDobToAgeOnCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('dob');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedSmallInteger('age')->nullable()->after('address');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('age');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->date('dob')->nullable()->after('address');
        });
    }
}
