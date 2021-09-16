<?php

use Illuminate\Support\Facades\Config;

function retrieveDeprPoles( $handle, $deprpole_name ) {
    $deptpole_name = urlencode( $deprpole_name );
    $baseurl = Config::get('base.url');
    $access_token = Config::get('access.token');        
    $url = $baseurl.'odpt:BusstopPole?acl:consumerKey='. $access_token . "&dc:title={$deptpole_name}";
    //出発地にバス停がいくつあってどの路線が通っているかを調べる
    $deprpole_infos = getDataFromAPI( $handle, $url);
    $odptpolenumber = 'odpt:busstopPoleNumber';
    $odptroutepattern = 'odpt:busroutePattern';
    $routepattern = [];
    //出発地バス停を通る路線を配列にまとめる
    foreach ( $deprpole_infos as $info ) {
        if ( $info->$odptpolenumber === null ) { continue; }
            $routepattern = array_merge( $routepattern, $info->$odptroutepattern );
    }
    return $routepattern;
}
