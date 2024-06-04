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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->engine = 'InnoDB'; 
            $table->bigIncrements('id');
            $table->decimal('amount',12,2);
            $table->boolean('is_credit');
            $table->bigInteger('receipt_id')->unsigned()->index();
            $table->foreign('receipt_id')->references('id')->on('finance_receipts')->onDelete('cascade');
            $table->bigInteger('wallet_id')->unsigned()->nullable();
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
