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
            $table->bigIncrements('id');
            $table->unsignedBigInteger("grade_id")->nullable();
            $table->unsignedInteger('academic_year');
            $table->unsignedTinyInteger('semester');
            $table->integer("week_number");
            $table->integer("lesson_number");
            $table->date("date")->nullable();
            $table->json("plans")->nullable();
            $table->unsignedTinyInteger("subject")->nullable();
            $table->text("outcome")->nullable();
            $table->text("outstanding_student")->nullable();
            $table->text("need_improvement_student")->nullable();
            $table->unsignedBigInteger("creator_id")->nullable();
            $table->unsignedBigInteger("approver_id")->nullable();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('set null');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('set null');
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
