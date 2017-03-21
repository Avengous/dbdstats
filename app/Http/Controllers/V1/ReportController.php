<?php

namespace App\Http\Controllers\V1;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function stats($summonerId, $matchId) {
        $query = DB::table('match_details_new')->select('*')->where([['champ_id', '=', $summonerId], ['match_id', '=', $matchId]])->get();
        $data = unserialize(collect($query->toArray()[0])['json']);

        foreach ($data['participantIdentities'] as $i => $id) {
            if ($id['player']['summonerId'] == $summonerId) {
                $stats = $data['participants'][$id['participantId']]['stats'];
            }
        }

        $stats['matchId'] = $data['matchId'];
        $stats['region'] = $data['region'];
        $stats['queueType'] = $data['queueType'];
        $stats['season'] = $data['season'];
        $stats['matchVersion'] = $data['matchVersion'];

        return Response($stats);
    }
}
