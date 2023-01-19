<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->integer('key');
            $table->integer('participants');
            $table->float('price');
            $table->string('type'); // enum('type', ['recreational', 'education', 'social', 'relaxation']);
            $table->string('activity');
            $table->string('link');
            $table->float('accessibility');
            $table->timestamp('loaded_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
