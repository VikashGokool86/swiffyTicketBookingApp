<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('O');
            $table->string('title');
            $table->text('description');
            $table->string('priority')->default('M');
            $table->unsignedBigInteger('assignee')->nullable();
            $table->string('stakeholders')->nullable();
            $table->string('tshirt_size')->nullable();
            $table->json('assets')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
