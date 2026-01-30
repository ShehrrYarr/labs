<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_tests', function (Blueprint $table) {
                $table->id();

            // ✅ price comes from this
            $table->foreignId('test_type_id')
                ->constrained('test_types')
                ->cascadeOnDelete();

            // Your existing dynamic category CRUD
            $table->foreignId('test_category_id')
                ->nullable()
                ->constrained('test_categories')
                ->nullOnDelete();

            // Your existing dynamic equipment CRUD
            $table->foreignId('required_equipment_id')
                ->nullable()
                ->constrained('equipments')
                ->nullOnDelete();

            // ✅ same params
            $table->string('test_name');                 // e.g., CBC
            $table->string('test_code')->nullable();     // optional
            $table->string('test_case_image')->nullable(); // optional path

            $table->text('description')->nullable();
            $table->string('unit')->nullable();
            $table->string('reference_range')->nullable();

            $table->string('reporting_time')->nullable(); // "6 Hours" / "24 Hours"
            $table->text('test_instruction')->nullable();
            $table->text('additional_notes')->nullable();

            $table->boolean('is_active')->default(true);

            // (optional) ordering
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            // helpful index
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
        Schema::dropIfExists('lab_tests');
    }
}
