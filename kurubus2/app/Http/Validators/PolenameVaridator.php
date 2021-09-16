<?php

namespace App\Http\Validators;

use Illuminate\Validation\Validator;

class Polenamevaridator extends Validator
{
    public function validatePolename( $attribute, $value, $parameters ) {
        $hoge = urlencode( $value );
        $baseurl = 'https://api-tokyochallenge.odpt.org/api/v4/';
        $access_token = 'e5f8c0903e7db287cbe3491292f9d6f42d3e204ea8970378cd7f4f48bc335b1e';
        $url = $baseurl.'odpt:BusstopPole?acl:consumerKey='. $access_token . "&dc:title={$hoge}";
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE);
        //$result = curl_exec( $ch );
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