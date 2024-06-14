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
        Schema::create('list_services', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('fee',12,2);
            $table->string('name');
            $table->string('description');
            $table->tinyInteger('laboratory_type')->unsigned()->index();
            $table->foreign('laboratory_type')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->integer('laboratory_id')->unsigned()->index();
            $table->foreign('laboratory_id')->references('id')->on('laboratories')->onDelete('cascade');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_services');
    }
};
