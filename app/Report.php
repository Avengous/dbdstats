<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'match_details_new';

    public function scopeBySummonerId($query, $id) {
    	return $query->where('champ_id', '=', $id);
    }

    public function scopeFindMatch($query, $summonerId, $matchId) {
        return $query->where([['champ_id', '=', $summonerId], ['match_id', '=', $matchId]]);
    }
}
