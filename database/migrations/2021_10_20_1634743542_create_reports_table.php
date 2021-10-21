<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    protected $table = "reports";

    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->unsignedBigInteger("grade_id")->nullable();
            $table->bigIncrements('id');
            $table->unsignedInteger('academic_year');
            $table->unsignedTinyInteger('semester');
            $table->integer("week_number");
            $table->integer("lesson_number");
            $table->date("date")->nullable();
            $table->json("topic")->nullable();
            $table->string("subject")->nullable();
            $table->text("outcome")->nullable();
            $table->text("outstanding_student")->nullable();
            $table->text("need_improvement_student")->nullable();
            $table->unsignedBigInteger("creator")->nullable();
            $table->unsignedBigInteger("approver")->nullable();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('set null');
            $table->foreign('creator')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approver')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
