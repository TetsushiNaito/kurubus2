<?php

//バス停名からバス停の情報を得る
function getPole( $handle, $name ) {
    $name = urlencode( $name );
    $baseurl = BASEURL;
    $access_token = ACCESSTOKEN;        
    $url = $baseurl.'odpt:BusstopPole?acl:consumerKey='. $access_token . "&dc:title={$name}";
    $results = getDataFromAPI( $handle, $url );
    $polls_array = [];
    //バス停番号がないデータは省く
    $busstoppolenumber = 'odpt:busstopPoleNumber';
    foreach ( $results as $result ) {
    //    if ( $result->$busstoppollnumber == '' ) { continue; }
        $polls_array[ $result->$busstoppolenumber ] = $result;
    }
    return $polls_array;
}
