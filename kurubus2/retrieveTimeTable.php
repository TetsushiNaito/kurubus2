<?php

// 調べるべき時刻表のリストを得る
function retrieveTimeTable( $day, $timetables ) {
    // 平日/土曜/休日の時刻表のどれを参照すべきか選択する  
    $filtereds = array_filter( $timetables, function($value) use ($day) {
        return preg_match( '/'.$day.'/', $value, $array );
    });
    $filtereds = array_unique( $filtereds );
    // 時刻表が無かった場合は抜ける
    return $filtereds;
}