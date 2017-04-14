<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDuoStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duo_stats', function (Blueprint $table) {
            $table->integer('duoId');
            $table->string('summonerIds');
			$table->integer('wins');
			$table->integer('losses');
			$table->float('winrate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('duo_stats');
    }
}
