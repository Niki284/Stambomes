<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('beheerder_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('avatar')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->date('death_date')->nullable();
            $table->foreignId('mother_id')->nullable();
            $table->foreignId('father_id')->nullable();
            $table->foreignId('spouse_id')->nullable();
            $table->foreignId('paternal_grandfather_id')->nullable();
            $table->foreignId('paternal_grandmother_id')->nullable();
            $table->foreignId('maternal_grandfather_id')->nullable();
            $table->foreignId('maternal_grandmother_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
