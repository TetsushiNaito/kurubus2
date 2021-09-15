<?php

const BASEURL = 'https://api-tokyochallenge.odpt.org/api/v4/';
const ACCESSTOKEN = 'e5f8c0903e7db287cbe3491292f9d6f42d3e204ea8970378cd7f4f48bc335b1e';

require_once 'getDataFromAPI.php';
require_once 'retrieveDeptPoles.php';
require_once 'getPole.php';
require_once 'retrieveRoutePattern.php';
require_once 'prepareTimetable.php';
require_once 'dayCheck.php';
require_once 'isHoliday.php';
require_once 'analysisTimetable.php';
require_once 'TimeTable.php';

$deprpole_name = '日吉駅東口';
//$deprpole_name = 'プラウドシティ日吉';
//$deprpole_name = '宮前西町';
//$deprpole_name = '江川町';
//$deprpole_name = '大倉山駅前';
//目的地のバス停
$destpole_name = '宮前西町';
//$destpole_name = '樋橋';
//$destpole_name = '江川町';
//$destpole_name = '越路';
//$destpole_name = '日大高校正門';
//$destpole_name = '日吉駅東口';
//$destpole_name = '港北区総合庁舎前';

$ch = curl_init();
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE);

//出発地のバス停を検索して、各バス停の情報を得る
$routepattern = retrieveDeprPoles( $ch, $deprpole_name );
//print_r( $startpolls );

//出発地のバス停の情報を得る
$deprpoles = getPole( $ch, $deprpole_name );

//目的地のバス停の情報を得る
$destpoles = getPole( $ch, $destpole_name );

//検索した各路線から目的地がある路線を選択する
[ $deprpole_owl, $destpole_owl, $route_candidates ] = retrieveRoutePattern( $ch, $routepattern, $deprpoles, $destpoles );

//選択した路線の時刻表を準備する（曜日でまとめただけ）
$timetable_mix = prepareTimetable( $ch, $route_candidates );

//ごちゃまぜの時刻表から必要な情報を抜き出してソートする
$times = analysisTimetable( $timetable_mix, $deprpole_owl );

$timetable = new TimeTable;
$timetable->depr_pole = $deprpole_name;
$timetable->dest_pole = $destpole_name;
$timetable->times = $times;

$nowtime = $timetable->getDeptTimeNow( $line_num );
//print_r( mb_convert_encoding( $nowtime, 'SJIS', 'UTF-8' ) );
$line = [];
$line_count = count($nowtime);
for ( $k = 0; $k < count($nowtime); $k++ ) {
    if ( ! isset ( $nowtime[$k]->route_name ) ) {
        $nowtime[$k]->route_name = '---';
    } 
    $line[$k] = [
        'id' => $k + 1,
        'dept_time' => $nowtime[$k]->dept_time,
        'route_name' => $nowtime[$k]->route_name
    ];
}
// 終バス以降のデータは終了にする
if ( $line_count < $line_num ) {
    for ( $l = $line_count; $l < $line_num; $l++ ) {
        $line[$l] = ['id' => $l +1 ,
                    'dept_time' => '終了',
                    'route_name' => '---'
                    ];
    }
} 
curl_close( $ch );
//return $line;
print_r( $line );
