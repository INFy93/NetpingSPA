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
        Schema::create('netping_bdcom', function (Blueprint $table) {
            $table->unsignedBigInteger('netping_id');
            $table->unsignedBigInteger('bdcom_id');
            $table->foreign('netping_id')->references('id')->on('netping')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('bdcom_id')->references('id')->on('bdcoms')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('netping_bdcom');
    }
};
