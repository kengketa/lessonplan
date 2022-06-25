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
        Schema::create('vocabs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('academic_year');
            $table->unsignedTinyInteger('semester');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('grade_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('subject_name');
            $table->string('vocab_en');
            $table->string('vocab_th')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vocabs');
    }
};
