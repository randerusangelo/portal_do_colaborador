<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Util extends Model
{
    public static function retirarAcentos( $string )
    {
        return preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'UTF-8//IGNORE', $string ) ); 
    }

    public static function retirarAcentosAux( $string )
    {
        return preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $string ) ); 
    }

    public static function somenteLetrasComEspaco( $string )
    {
        return preg_replace("/[^A-Za-z ]/", "", $string );
    }

    public static function somenteLetrasSemEspaco( $string )
    {
        return preg_replace("/[^A-Za-z]/", "", $string );
    }

    public static function mask( $val, $mask )
    {
        $aReplace = array( '.' => '', '-' => '' );
        $val      = strtr( $val, $aReplace );

        $maskared = '';
        $k        = 0;
        
        for( $i = 0; $i <= strlen( $mask ) - 1; $i++ ){
            
            if($mask[$i] == '#'){
                if( isset( $val[$k] ) ) $maskared .= $val[$k++];

            } else {
                if( isset( $mask[$i] ) ) $maskared .= $mask[$i];
            
            }
        
        }
        
        return $maskared;
    }

}
