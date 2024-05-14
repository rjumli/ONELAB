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
        Schema::create('finance_receipts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('number')->unique();
            $table->bigInteger('op_id')->unsigned()->index();
            $table->foreign('op_id')->references('id')->on('finance_ops')->onDelete('cascade');
            $table->integer('orseries_id')->unsigned()->index();
            $table->foreign('orseries_id')->references('id')->on('finance_orseries')->onDelete('cascade');
            $table->bigInteger('payor_id')->unsigned()->index();
            $table->foreign('payor_id')->references('id')->on('customers')->onDelete('cascade');
            $table->integer('created_by')->unsigned()->index();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('finance_receipts');
    }
};
