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
        Schema::create('configurations', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('acronym',20)->default('DOST-IX');
            $table->string('name',100)->default('Department of Science and Technology - IX');
            $table->json('laboratories');
            $table->boolean('samplecode_year');
            $table->string('tsr_count')->nullable();
            $table->string('sample_count')->nullable();
            $table->integer('laboratory_id')->unsigned()->index();
            $table->foreign('laboratory_id')->references('id')->on('laboratories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
