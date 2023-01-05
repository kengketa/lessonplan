<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('misbehaviors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("report_id")->nullable();
            $table->string('name');
            $table->text('behavior');
            $table->string('subject');
            $table->unsignedBigInteger('teacher_id')->nullable();

            $table->foreign('report_id')->references('id')->on('reports')->onDelete('set null');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('misbehaviors');
    }
};
