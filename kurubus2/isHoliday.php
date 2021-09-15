<?php
function isHoliday( $target_day ) {
    $calendarID = 'ja.japanese#holiday@group.v.calendar.google.com';
    $api_key = 'AIzaSyCymDtM7LChdd4-EPimWjdzh3BIZ5a5wnk';

    $calendarID = urlencode( $calendarID );
    $url = 'https://www.googleapis.com/calendar/v3/calendars/'.$calendarID.'/events?';

    $start = date('c', strtotime( $target_day ) );
    // 3ヶ月先まで調べる
    $end = date('c', time() + 60*60*24*31*3 );
    //print "$end\n";

    $query = [
        'key' => $api_key,
        'singleEvents' => 'true', //trueで終日設定ではないイベントを返す(デフォルトはfalse)
        'orderBy' => 'startTime',
        'timeMin' => $start,
        'timeMax' => $end, 
        'maxResults' => 10,
        'timeZone' => 'Asia/Tokyo'
    ];
    $url = $url.http_build_query($query);
    //print "$url\n";
    $ch = curl_init( $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
    $result = json_decode( curl_exec( $ch ), FALSE );
    $holiday = $result->items[0]->start->date;
    if ( $target_day === $holiday ) {
        return 1;
    }
    else { return 0; }
}
