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
        Schema::create('tsrs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('code')->unique()->nullable();
            // $table->json('mode');
            $table->integer('laboratory_id')->unsigned()->index();
            $table->foreign('laboratory_id')->references('id')->on('laboratories')->onDelete('cascade');
            $table->tinyInteger('laboratory_type')->unsigned()->index();
            $table->foreign('laboratory_type')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->tinyInteger('purpose_id')->unsigned()->index();
            $table->foreign('purpose_id')->references('id')->on('list_dropdowns')->onDelete('cascade');
            $table->tinyInteger('status_id')->unsigned()->index();
            $table->foreign('status_id')->references('id')->on('list_statuses')->onDelete('cascade');
            $table->bigInteger('customer_id')->unsigned()->index();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->bigInteger('conforme_id')->unsigned()->index();
            $table->foreign('conforme_id')->references('id')->on('customer_conformes')->onDelete('cascade');
            $table->integer('received_by')->unsigned()->index();
            $table->foreign('received_by')->references('id')->on('users')->onDelete('cascade');
            $table->date('due_at')->nullable();
            $table->datetime('released_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tsrs');
    }
};
