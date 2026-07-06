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
        if (!Schema::hasColumn('clock_ins', 'comment')) {
            Schema::table('clock_ins', function (Blueprint $table) {
                $table->text('comment')->nullable()->after('clock_out');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clock_ins', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
    }
};
