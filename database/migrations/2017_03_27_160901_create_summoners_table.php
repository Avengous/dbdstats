<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSummonersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summoners', function (Blueprint $table) {
            $table->integer('summonerId');
            $table->integer('name');
			$table->string('soloTier');
			$table->string('soloDivision');
			$table->integer('soloWins');
			$table->integer('soloLosses');
			$table->integer('soloLeaguePoints');
			$table->string('flexTier');
			$table->string('flexDivision');
			$table->integer('flexWins');
			$table->integer('flexLosses');
			$table->integer('flexLeaguePoints');	
			$table->date('revisionDate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('summoners');
    }
}
