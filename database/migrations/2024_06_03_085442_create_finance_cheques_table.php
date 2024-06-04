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
        Schema::create('finance_cheques', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('bank');
            $table->string('number')->unique();
            $table->decimal('amount',12,2); 
            $table->bigInteger('receipt_id')->unsigned()->index();
            $table->foreign('receipt_id')->references('id')->on('finance_receipts')->onDelete('cascade');
            $table->date('cheque_at');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_cheques');
    }
};
