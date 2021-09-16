<?php

namespace App\Http\Controllers;

require_once 'getDataFromAPI.php';
require_once 'retrieveDeprPoles.php';
require_once 'getPole.php';
require_once 'retrieveRoutePattern.php';
require_once 'prepareTimetable.php';
require_once 'dayCheck.php';
require_once 'isHoliday.php';
require_once 'analysisTimetable.php';
require_once 'TimeTable.php';

//トップページ
const TOPPAGE = 'http://localhost/';
// バス停の新規登録画面
const SUBMITPAGE = 'http://localhost/submit/';

use App\Http\Requests\PolenameRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use PhpParser\Node\Expr\AssignOp\ShiftLeft;
use Time;

// base url
Config::set('base.url', 'https://api-tokyochallenge.odpt.org/api/v4/') ;

// アクセストークン
Config::set('access.token', 'e5f8c0903e7db287cbe3491292f9d6f42d3e204ea8970378cd7f4f48bc335b1e');

class SearchController extends Controller
{
    public function index( Request $request, $depr_pole, $dest_pole, $line_num, $holiday=0 ) {
        //初回はCookieが空なので必ず登録画面に飛ばす
        if ( ! isset( $_COOKIE['depr_poles'] ) || ! isset( $_COOKIE['dest_poles'] ) ) {
         //   header( 'Location: ' . SUBMITPAGE );
            return redirect( '/foo/bar/0' );
        }        
        $deprs = explode( ':', $request->cookie( 'depr_poles' ) );
        $dests = explode( ':', $request->cookie( 'dest_poles' ) );
        $line_num = $request->cookie( 'line_num' );
        if ( $depr_pole == '' ) {
            $depr_pole = array_shift( $deprs );
        } else {
            if ( ! $this->_check_pole( $depr_pole ) ) {
                return view( 'invalid' );
            }
            array_shift( $deprs );
        }
        if ( $dest_pole == '' ) {
            $dest_pole = array_shift( $dests );
        } else {
            if ( ! $this->_check_pole( $dest_pole ) ) {
                return view( 'invalid' );
            }
            array_shift( $dests );
        }
        if ( $line_num > 10 ) {
            return view( 'invalid' );
        }
        $showTimeTable = new showTimeTable;
        $timetable_lines = $showTimeTable->show_timetable( $depr_pole, $dest_pole, $line_num, $holiday );
        $timetable_lines_JSON = json_encode($timetable_lines);
        $data = [
            'deprs' => $deprs,
            'dests' => $dests,
            'depr_pole' => $depr_pole,
            'dest_pole' => $dest_pole,
            'line_num' => $line_num,
            'timetable_lines' => $timetable_lines,
            'timetable_lines_JSON' => $timetable_lines_JSON
        ];
        return view( 'index', $data );
    }

    public function api( Request $request, $depr_pole, $dest_pole, $line_num, $holiday=0 ) {
        $showTimeTable = new showTimeTable;
        $timetable_lines = $showTimeTable->show_timetable( $depr_pole, $dest_pole, $line_num, $holiday );
        $timetable_lines = json_encode($timetable_lines);
 //       $data = [
//            'timetable_lines' => $timetable_lines,
//            'timetable_lines_JSON' => $timetable_lines
//        ];
//        return view( 'api', $data );
        print $timetable_lines;
    }

    public function post( PolenameRequest $request ) {

        $depr_pole = $request->depr_pole;
        $dest_pole = $request->dest_pole;
        
        $response = response()->view( 'confirm', [ 
            'depr_pole' => $depr_pole,
            'dest_pole' => $dest_pole,
            'line_num' => $request->line_num
        ] );
        
        if ( isset( $_COOKIE['depr_poles'] ) ) {
            $cookie = $request->cookie('depr_poles');
            // 登録されていないバス停名のみ追加する
            $array = explode( ':', $cookie );
            if ( array_search( $depr_pole, $array ) === false ) {
                $response->cookie( 'depr_poles', $depr_pole . ':' . $cookie, 86400, '', '', '', false );
            }
        }
        else {
 //           $response->cookie( 'depr_poles', $depr_pole . ':' );
            $response->cookie( 'depr_poles', $depr_pole, 86400, '', '', '', false );
        }
        if ( isset( $_COOKIE['dest_poles'] ) ) {
            $cookie = $request->cookie( 'dest_poles');
            // 登録されていないバス停名のみ追加する
            $array = explode( ':', $cookie );
            if ( array_search( $dest_pole, $array ) === false ) {
                $response->cookie( 'dest_poles', $dest_pole . ':' . $cookie, 86400, '', '', '', false );
            }
        }
        else {
//            $response->cookie( 'dest_poles', $dest_pole . ':' );
            $response->cookie( 'dest_poles', $dest_pole, 86400, '', '', '', false );
        }
        $response->cookie( 'line_num', $request->line_num, 86400, '', '', '', false );
        return $response;
    }

    function _check_pole( $pole_name ) {
        $baseurl = Config::get('base.url');
        $access_token = Config::get('access.token');        
        $hoge = urlencode( $pole_name );
        $url = $baseurl.'odpt:BusstopPole?acl:consumerKey='. $access_token . "&dc:title={$hoge}";
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec( $ch );
        $result = json_decode( curl_exec( $ch ), FALSE );
        curl_close( $ch );
        if (isset( $result[0] ) ) {
            return true;
        }
        else {
            return false; 
        }
    }
}