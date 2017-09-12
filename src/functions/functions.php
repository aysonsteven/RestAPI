<?php
namespace functions;

class functions{
    function json_error( $code, $message=null ) {

        if ( is_array($code) ) $res = $code;
        else $res = [ 'code' => $code, 'message' => $message ];
        echo json_encode( $res );
        exit;
    }
    function http_post ($url, $data, $json_decode = false, $debug = false)
    {
        if ( ! is_array( $data ) ) $data_url = $data;
        else $data_url = http_build_query ($data);
        $data_len = strlen ($data_url);
        $context = stream_context_create (
            array (
                'http'=> array (
                    'method'    => 'POST',
                    'protocol_version' => 1.1,
                    'header'    =>"Content-Type: application/x-www-form-urlencoded\r\nConnection: close\r\nContent-Length: $data_len\r\n",
                    'content'   => $data_url,
                    'timeout'   => 4
                )
            )
        );
        if ($debug) {
            echo "request url: " . SERVER_URL . "?$data_url\n";
        }
        $content = file_get_contents ( $url, false, $context );
        if ( $json_decode ) {
            $re = @json_decode( $content, true );
            if ( $error = json_decode_error() ) {
                return ['code' => -11, 'message' => " >> Failed on decode JSON data from server $error - It may be server error. Data from server: $content"];
            }
            else return $re;
        }
        else return $content;
    }

    function json_decode_error() {
        $error = json_last_error();
        if ( $error ) {

            switch ( $error ) {
                case JSON_ERROR_NONE: return ' - No errors';
                case JSON_ERROR_DEPTH: return' - Maximum stack depth exceeded';
                case JSON_ERROR_STATE_MISMATCH: return ' - Underflow or the modes mismatch';
                case JSON_ERROR_CTRL_CHAR: return ' - Unexpected control character found';
                case JSON_ERROR_SYNTAX: return ' - Syntax error, malformed JSON';
                case JSON_ERROR_UTF8: return ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                default: return ' - Unknown error';
            }

        }
        else return false;
    }

    public function getSessionID( $id, $password ) {
//        if ( $error = validate_id( $id ) ) return error( -20075, $error );
//        $user = $this->get( $id );
//        if ( empty($user) ) return error(-20070, 'user does not exist');
//        if ( $user['password'] != encrypt_password( $password ) ) return error( -20071, 'incorrect password');
        return substr(get_session_id( $user['idx'] ), 4);
    }
}