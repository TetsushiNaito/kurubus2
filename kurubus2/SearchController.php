<?php

namespace App\Http\Controllers;

require_once 'dayCheck.php';
require_once 'findDeptPolls.php';
require_once 'getDataFromAPI.php';
require_once 'getPoll.php';
require_once 'isHoliday.php';
require_once 'makeTable.php';
require_once 'retrieveDeptPolls.php';
require_once 'retrieveTimeTable.php';
require_once 'TimeTable.php';

//トップページ
const TOPPAGE = 'http://localhost/';
// バス停の新規登録画面
const SUBMITPAGE = 'http://localhost/submit/';

use App\Http\Requests\PollnameRequest;
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
    public function index( Request $request, $depr_poll, $dest_poll, $line_num, $holiday=0 ) {
        //初回はCookieが空なので必ず登録画面に飛ばす
        if ( ! isset( $_COOKIE['depr_polls'] ) || ! isset( $_COOKIE['dest_polls'] ) ) {
         //   header( 'Location: ' . SUBMITPAGE );
            return redirect( '/foo/bar/0' );
        }        
        $deprs = explode( ':', $request->cookie( 'depr_polls' ) );
        $dests = explode( ':', $request->cookie( 'dest_polls' ) );
        $line_num = $request->cookie( 'line_num' );
        if ( $depr_poll == '' ) {
            $depr_poll = array_shift( $deprs );
        } else {
            if ( ! $this->_check_poll( $depr_poll ) ) {
                return view( 'invalid' );
            }
            array_shift( $deprs );
        }
        if ( $dest_poll == '' ) {
            $dest_poll = array_shift( $dests );
        } else {
            if ( ! $this->_check_poll( $dest_poll ) ) {
                return view( 'invalid' );
            }
            array_shift( $dests );
        }
        if ( $line_num > 10 ) {
            return view( 'invalid' );
        }
        $showTimeTable = new showTimeTable;
        $timetable_lines = $showTimeTable->show_timetable( $depr_poll, $dest_poll, $line_num, $holiday );
        $timetable_lines_JSON = json_encode($timetable_lines);
        $data = [
            'deprs' => $deprs,
            'dests' => $dests,
            'depr_poll' => $depr_poll,
            'dest_poll' => $dest_poll,
            'line_num' => $line_num,
            'timetable_lines' => $timetable_lines,
            'timetable_lines_JSON' => $timetable_lines_JSON
        ];
        return view( 'index', $data );
    }

    public function api( Request $request, $depr_poll, $dest_poll, $line_num, $holiday=0 ) {
        $showTimeTable = new showTimeTable;
        $timetable_lines = $showTimeTable->show_timetable( $depr_poll, $dest_poll, $line_num, $holiday );
        $timetable_lines = json_encode($timetable_lines);
 //       $data = [
//            'timetable_lines' => $timetable_lines,
//            'timetable_lines_JSON' => $timetable_lines
//        ];
//        return view( 'api', $data );
        print $timetable_lines;
    }

    public function post( PollnameRequest $request ) {

        $depr_poll = $request->depr_poll;
        $dest_poll = $request->dest_poll;
        
        $response = response()->view( 'confirm', [ 
            'depr_poll' => $depr_poll,
            'dest_poll' => $dest_poll,
            'line_num' => $request->line_num
        ] );
        
        if ( isset( $_COOKIE['depr_polls'] ) ) {
            $cookie = $request->cookie('depr_polls');
            // 登録されていないバス停名のみ追加する
            $array = explode( ':', $cookie );
            if ( array_search( $depr_poll, $array ) === false ) {
                $response->cookie( 'depr_polls', $depr_poll . ':' . $cookie, 86400, '', '', '', false );
            }
        }
        else {
 //           $response->cookie( 'depr_polls', $depr_poll . ':' );
            $response->cookie( 'depr_polls', $depr_poll, 86400, '', '', '', false );
        }
        if ( isset( $_COOKIE['dest_polls'] ) ) {
            $cookie = $request->cookie( 'dest_polls');
            // 登録されていないバス停名のみ追加する
            $array = explode( ':', $cookie );
            if ( array_search( $dest_poll, $array ) === false ) {
                $response->cookie( 'dest_polls', $dest_poll . ':' . $cookie, 86400, '', '', '', false );
            }
        }
        else {
//            $response->cookie( 'dest_polls', $dest_poll . ':' );
            $response->cookie( 'dest_polls', $dest_poll, 86400, '', '', '', false );
        }
        $response->cookie( 'line_num', $request->line_num, 86400, '', '', '', false );
        return $response;
    }

    function _check_poll( $poll_name ) {
        $baseurl = Config::get('base.url');
        $access_token = Config::get('access.token');        
        $hoge = urlencode( $poll_name );
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