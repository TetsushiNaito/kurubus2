<?php

function getDataFromAPI( $handle, $url ) {
        curl_setopt( $handle, CURLOPT_URL, $url );
        curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );
        //print "$url\n";
        $result = curl_exec( $handle );
        $code = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        if ( $code != 200 ) {
                if ( $code == 404 ) {
                        $result = -1;  
                } 
                elseif ( $code >= 500 ) {
                        $result = -11;                        
                }
        } else {
                $result = json_decode( $result, FALSE );
        }
        return $result;
}
