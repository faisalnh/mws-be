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
        Schema::create('classes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign key ke tabel grades
            $table->uuid('grade_id');

            // Nama kelas
            $table->string('name', 150);

            // Enum type sesuai rancangan (class_type)
            $table->enum('grade_level', [
                'sd',
                'smp',
            ])->nullable();


            // Timestamp default Laravel (created_at & updated_at)
            $table->timestamps();

            // Soft delete (deleted_at)
            $table->softDeletes();

            // Foreign key constraint
            $table->foreign('grade_id')
                ->references('uuid')
                ->on('grades')
                ->cascadeOnDelete();

            // Unique constraint untuk kombinasi grade_id dan name
            $table->unique(['grade_id', 'name'], 'classes_grade_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
