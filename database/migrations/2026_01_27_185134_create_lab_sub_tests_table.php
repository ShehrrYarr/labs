<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabSubTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_sub_tests', function (Blueprint $table) {
            $table->id();

            // ✅ parent main test
            $table->foreignId('lab_test_id')
                ->constrained('lab_tests')
                ->cascadeOnDelete();

            // ✅ same params as lab_tests (as per client)
            $table->foreignId('test_type_id')
                ->constrained('test_types')
                ->cascadeOnDelete();

            $table->foreignId('test_category_id')
                ->nullable()
                ->constrained('test_categories')
                ->nullOnDelete();

            $table->foreignId('required_equipment_id')
                ->nullable()
                ->constrained('equipments')
                ->nullOnDelete();

            $table->string('test_name');                 // e.g., WBC
            $table->string('test_code')->nullable();
            $table->string('test_case_image')->nullable();

            $table->text('description')->nullable();
            $table->string('unit')->nullable();
            $table->string('reference_range')->nullable();

            $table->string('reporting_time')->nullable();
            $table->text('test_instruction')->nullable();
            $table->text('additional_notes')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['test_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lab_sub_tests');
    }
}
