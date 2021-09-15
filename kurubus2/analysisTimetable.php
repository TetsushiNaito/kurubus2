<?php

function analysisTimetable( $timetable, $deprpoles ) {
    $dctitle = 'dc:title';
    $odpttimetableobj = 'odpt:busTimetableObject';
    $odptbusstoppole = 'odpt:busstopPole';
    $odptarrivaltime = 'odpt:arrivalTime';
    $odptdeprtime = 'odpt:departureTime';
    $timeobjs = [];
    foreach( $timetable as $table ) {
        //系統名
        $route_name = $table->$dctitle;

        //出発地バス停の時刻のオブジェクト（の配列）を取り出す
        foreach( $deprpoles as $deprpole ) {
            $hoge = $table->$odpttimetableobj;
            $buffer = array_filter( $table->$odpttimetableobj, function($v) use ( $odptbusstoppole, $deprpole ) {
                return $v->$odptbusstoppole == $deprpole; } );
            $timeobjs = array_merge( $timeobjs, $buffer );
        }
        foreach( $timeobjs as $timeobj ) {
            if ( property_exists( $timeobj, $odptdeprtime ) ) {
                $depr_time = $timeobj->$odptdeprtime;
            }
            else {
                $depr_time = $timeobj->$odptarrivaltime;
            }
        }
        //時刻表の塊を一度オブジェクトの配列に落とし込む
        $time = new Time;
        $time->depr_time = $depr_time;
        $time->route_name = $route_name;
        $timesarray[] = $time;
    }
    //時刻表の塊をソートする
    usort( $timesarray, function( $a, $b ) {
        return strtotime( $a->depr_time ) <=> strtotime( $b->depr_time );
    } );
    //時刻表の配列からダブりを除く
    $timesarray = array_unique( $timesarray, SORT_REGULAR );

    return $timesarray;
}