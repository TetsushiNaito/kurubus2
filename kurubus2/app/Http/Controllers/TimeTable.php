<?php

/*
Time: 時刻1つごとのオブジェクト
プロパティ：
depr_time: 出発時刻
route_name: 路線名
*/

class Time {
	public string $depr_time;
	public string $route_name;
}

/*
TimeTable: 時刻表オブジェクト
プロパティ：
dept_pole: 出発地バス停名
dest_pole: 目的地バス停名
times: Timeオブジェクトの配列
メソッド：
getDeptTimeNow: 現在時刻から最も近い出発時刻を検索する
　引数：
　num: 表示する候補の数
*/

class TimeTable {
	public string $dept_pole;
	public string $dest_pole;
	public array $times;

	public function getDeptTimeNow( $num ) : array {
		$result = [];
		$now_time = date('H:i');
        // print "$now_time\n";
		for ( $i = 0; $i < count( $this->times ); $i++ ) {
			if ( strtotime( $this->times[$i]->depr_time ) > strtotime( $now_time ) ) { break; }
        }
        for ( $j = 0; $j < $num; $j++ ) {
            //print "hogehoge\n";
			// 終バス以降は表示対象にしない
			if ( $i + $j >= count( $this->times) ) { break; }
			$result[] = $this->times[ $i + $j ];
		}
        return $result;
	}
}
