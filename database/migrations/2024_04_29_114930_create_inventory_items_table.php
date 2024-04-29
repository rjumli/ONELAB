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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('img');
            $table->integer('reorder')->default(0);
            $table->integer('unit');
            $table->tinyInteger('unit_id')->unsigned()->index();
            $table->foreign('unit_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->tinyInteger('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->tinyInteger('laboratory_type')->unsigned()->index();
            $table->foreign('laboratory_type')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->integer('laboratory_id')->unsigned()->index();
            $table->foreign('laboratory_id')->references('id')->on('laboratories')->onDelete('cascade');
            $table->boolean('is_equipment');
            $table->boolean('is_active')->default(1);
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
