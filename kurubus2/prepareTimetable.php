<?php

function prepareTimetable ( $handle, $route_candidates ) {
    $baseurl = BASEURL;
    $access_token = ACCESSTOKEN;
    $odptcalendar = 'odpt:calendar';
    $Weekday = [];
    $Saturday = [];
    $Holiday = [];
    foreach( $route_candidates as $route ) {
        $url = $baseurl.'odpt:BusTimetable?acl:consumerKey='. $access_token . "&odpt:busroutePattern={$route}";
        $results = getDataFromAPI( $handle, $url );
        if ( is_string( $results ) ) { continue; }
        // 曜日によってデータを分ける
        foreach( $results as $result ) {
            if ( preg_match( '/Weekday/', $result->$odptcalendar, $array ) ) {
                $Weekday[] = $result; 
            }
            elseif ( preg_match( '/Saturday/', $result->$odptcalendar, $array ) ) {
                $Saturday[] = $result;
            }
            elseif ( preg_match( '/Holiday/', $result->$odptcalendar, $array ) ) {
                $Holiday[] = $result;
            }
        }
    } 
    //今日は平日か土曜か日祝日か確認して、どの時刻表を使うか決める
    $day = dayCheck();
    //もし土曜日だってのに土曜日の時刻表がなかったら、平日の時刻表を使う
    if ( $day == 'Saturday' && count($Saturday) == 0 ) {
        $timetable = $Weekday;
    }
    else {
        $timetable = $$day;
    }
    return $timetable;
}