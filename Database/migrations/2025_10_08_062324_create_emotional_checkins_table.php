<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emotional_checkins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id'); // mengacu ke users.id

            // 🧠 Informasi dasar
            $table->enum('role', ['student', 'teacher', 'parent', 'staff']);
            $table->json('mood')->nullable();

            // 🌦️ Internal weather
            $table->enum('internal_weather', [
                'sunny_clear',
                'partly_cloudy',
                'light_rain',
                'thunderstorms',
                'tornado_watch',
                'snowy_still',
                'post_storm_rainbow',
                'foggy',
                'heatwave',
                'windy'
            ])->nullable();

            // 🔥 Intensitas & Catatan
            $table->integer('presence_level');
            $table->integer('capasity_level');
            $table->text('note')->nullable();
            $table->timestamp('checked_in_at');

            // ⚡ Capacity Levels (digabung di sini)
            $table->enum('energy_level', ['low', 'medium', 'high'])->nullable();
            $table->enum('balance', ['unbalanced', 'balanced', 'highly_balanced'])->nullable();
            $table->enum('load', ['light', 'moderate', 'heavy'])->nullable();
            $table->enum('readiness', ['not_ready', 'somewhat_ready', 'ready'])->nullable();

            $table->unsignedBigInteger('contact_id')->nullable(); // null jika no_need

            $table->timestamps();

            // 🔗 Foreign key dan index
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index(['user_id', 'checked_in_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emotional_checkins');
    }
};
