<?php

use function PHPSTORM_META\type;

function retrieveRoutePattern( $handle, $routepattern, $deprpoles, $destpoles ) {
    $baseurl = BASEURL;
    $access_token = ACCESSTOKEN;
    $odptpoleorder ='odpt:busstopPoleOrder';
    $owlsameas ='owl:sameAs';
    $odptindex ='odpt:index';
    $odptbusstoppole = 'odpt:busstopPole';
    //路線ごとに、目的地のバス停の有無を調べる
    $route_candidates = [];
    foreach( $routepattern as $pattern ) {
        $order = [];
        $url = $baseurl.'odpt:BusroutePattern?acl:consumerKey='. $access_token . "&owl:sameAs={$pattern}";
        $routeinfo = getDataFromAPI( $handle, $url );
        if ( is_string($routeinfo[0] ) ) { continue; }
        $poleorder = $routeinfo[0]->$odptpoleorder;
        foreach( $poleorder as $pole ) {
            $order[$pole->$odptindex] = $pole->$odptbusstoppole;
        }
        //出発地と目的地の位置関係の組み合わせを調べて、出発地→目的地となる路線を検索候補にする
        foreach ( $destpoles as $destpole ) {
            foreach ( $deprpoles as $deprpole ) {
                if ( ( $deprnum = array_search( $deprpole->$owlsameas, $order ) ) === false ) { continue; }
                if ( ( $destnum = array_search( $destpole->$owlsameas, $order ) ) === false ) { continue; }
                if ( $deprnum < $destnum ) {
                    $deprpole_owl[] = $deprpole->$owlsameas;
                    $destpole_owl[] = $destpole->$owlsameas;
                    $route_candidates[] = $pattern;
                }
            }

        }
    }
    $deprpole_owl = array_unique( $deprpole_owl );
    $destpole_owl = array_unique( $destpole_owl );
    return [ $deprpole_owl, $destpole_owl, $route_candidates ];
}