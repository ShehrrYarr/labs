<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
           $table->id();

            // Customer login user (users.category = 'customer')
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unique('user_id');

            // Which branch created this customer (nullable for admin-created)
            $table->foreignId('created_by_branch_id')->nullable()->constrained('branches')->nullOnDelete();

            // Optional: which user created (admin or branch user)
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            // Customer profile fields (basic for now)
            $table->string('phone')->nullable();
            $table->string('ref_by')->nullable();
            $table->text('address')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('is_active');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
