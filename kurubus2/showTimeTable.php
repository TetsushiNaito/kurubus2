<?php

namespace App\Http\Controllers;

class showTimeTable {
    public function show_timetable( $deptpoll_name, $destpoll_name, $line_num, $holiday ) {
        //出発地のバス停
        //$deptpoll_name = '日吉駅東口';
        //$deptpoll_name = 'プラウドシティ日吉';
        //$deptpoll_name = '宮前西町';
        //$deptpoll_name = '江川町';

        //目的地のバス停
        //$destpoll_name = '宮前西町';
        //$destpoll_name = '樋橋';
        //$destpoll_name = '江川町';
        //$destpoll_name = '越路';
        //$destpoll_name = '日大高校正門';
        //$destpoll_name = '日吉駅東口';

        // 表示する候補の数
        //$line_num = 3;

        // 今日は平日土曜休日のいずれなのかを調べる
        if ( $holiday == 1 ) { //強制休日ダイヤモード
            $day = 'Holiday';
        } else {
            $day = dayCheck();
        }
        //$url = BASEURL . "place/odpt:Station?lon={$lon}&lat={$lat}&radius={$radius}&acl:consumerKey=" . ACCESSTOKEN;
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE);

        //出発地のバス停を検索して、各バス停の情報を得る
        $startpolls = retrieveDeptPolls( $ch, $deptpoll_name );
        //print_r( $startpolls );

        //目的地のバス停の情報を得る
        $destpolls = getPoll( $ch, $destpoll_name );

        //出発地バス停から、目的地に行く路線の時刻表を特定する
        [ $route_names_table, $table_candidate ] = findDeptPolls( $ch, $startpolls, $destpolls );

        //時刻表が無かった場合は -1 を返す
        if ( gettype( $table_candidate) != 'array' ) {
            return -1;
        }
        if ( count( $table_candidate ) == 0 || $table_candidate[0] == '' ) {
            return -1;
        }

        // 調べるべき時刻表を特定する
        $tables = retrieveTimeTable( $day, $table_candidate );
        //print_r( $tables );

        $timetable = new TimeTable;
        $timetable->depr_poll = $deptpoll_name;
        $timetable->dest_poll = $destpoll_name;
        $timetable->times = makeTable( $ch, $route_names_table, $tables );

        $nowtime = $timetable->getDeptTimeNow( $line_num );
        //print_r( mb_convert_encoding( $nowtime, 'SJIS', 'UTF-8' ) );
        $line = [];
        $line_count = count($nowtime);
        for ( $k = 0; $k < count($nowtime); $k++ ) {
            if ( ! isset ( $nowtime[$k]->note ) ) {
                $nowtime[$k]->note = '---';
            } 
            $line[$k] = [
                'id' => $k + 1,
                'dept_time' => $nowtime[$k]->dept_time,
                'route_name' => $nowtime[$k]->route_name,
                'note' => $nowtime[$k]->note ];
        }
        // 終バス以降のデータは終了にする
        if ( $line_count < $line_num ) {
            for ( $l = $line_count; $l < $line_num; $l++ ) {
                $line[$l] = ['id' => $l +1 ,
                            'dept_time' => '終了',
                            'route_name' => '---',
                            'note' => '---' ];
            }
        } 
        curl_close( $ch );
        return $line;
        //print_r( $table_all );

    }
}