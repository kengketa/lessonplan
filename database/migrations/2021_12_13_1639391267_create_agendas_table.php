<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendasTable extends Migration
{
    protected $table = "agendas";

    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("meeting_id");
            $table->string("topic");
            $table->text("detail")->nullable();
            $table->text("decision")->nullable();
            $table->timestamps();

            $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendas');
    }
}
