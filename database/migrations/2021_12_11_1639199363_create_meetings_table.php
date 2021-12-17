<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    protected $table = "meetings";

    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("school_id");
            $table->string("title");
            $table->dateTime("date")->nullable();
            $table->string('location')->nullable();
            $table->json('attendee')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meetings');
    }
}
