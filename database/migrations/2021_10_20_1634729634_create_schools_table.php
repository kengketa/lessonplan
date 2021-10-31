<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    protected $table = "schools";

    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->text("address")->nullable();
            $table->json('subjects')->nullable();
            $table->unsignedDecimal('lat', 11, 8)->nullable();
            $table->unsignedDecimal('lng', 11, 8)->nullable();
            $table->unsignedInteger('radius')->nullable();
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
        Schema::dropIfExists('schools');
    }
}
