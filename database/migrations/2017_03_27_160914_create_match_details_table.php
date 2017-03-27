<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('matchId');
			$table->integer('summonerId');
			$table->string('matchVersion');
			$table->string('platformId');
			$table->integer('mapId');
			$table->date('matchCreation');
			$table->string('matchMode');
			$table->integer('matchDuration');
			$table->string('season');
			$table->string('queueType');
			$table->string('matchType');
			$table->string('matchHistoryUri');
			$table->integer('teamId');
			$table->integer('championId');
			$table->integer('wardsKilled');
			$table->integer('wardsPlaced');
			$table->integer('largestMultiKill');
			$table->integer('champLevel');
			$table->boolean('winner');
			$table->integer('goldEarned');
			$table->integer('minionsKilled');
			$table->integer('neutralMinionsKilled');
			$table->integer('kills');
			$table->integer('deaths');
			$table->integer('assists');
			$table->integer('totalDamageDealtToChampions');
			$table->integer('totalDamageTaken');
			$table->string('csDiffPerMinDeltas');
			$table->float('pctTeamDamageDealtToChampions');
			$table->float('pctTeamDamageTaken');
			$table->float('pctTeamGoldShare');
			$table->float('pctKillParticipation');
			$table->float('pctTeamDeaths');		
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_details');
    }
}
