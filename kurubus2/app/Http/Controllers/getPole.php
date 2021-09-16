<?php

use Illuminate\Support\Facades\Config;

//バス停名からバス停の情報を得る
function getPole( $handle, $name ) {
    $name = urlencode( $name );
    $baseurl = Config::get('base.url');
    $access_token = Config::get('access.token');        
    $url = $baseurl.'odpt:BusstopPole?acl:consumerKey='. $access_token . "&dc:title={$name}";
    $results = getDataFromAPI( $handle, $url );
    $poles_array = [];
    //バス停番号がないデータは省く
    $busstoppolenumber = 'odpt:busstopPoleNumber';
    foreach ( $results as $result ) {
    //    if ( $result->$busstoppolenumber == '' ) { continue; }
        $poles_array[ $result->$busstoppolenumber ] = $result;
    }
    return $poles_array;
}
