<?php
namespace LazyRecord;
use LazyRecord\Types\DateTime;

class Deflator
{


    /** 
     * provide a custom deflator for data type
     * xxx:
     */
    static function register($dataType)
    {

    }

    static function deflate($value,$dataType = null)
    {
        if( $value === null || $dataType === null )
            return $value;

        /* respect the data type to inflate value */
        if( $dataType == 'int' ) {
            return (int) $value;
        }
        elseif( $dataType == 'str' ) {
            return (string) $value;
        }
        elseif( $dataType == 'bool' ) {
            if( strcasecmp( 'false', $value ) == 0 || $value == '0' ) {
                return false;
            } 
            elseif( strcasecmp( 'true', $value ) == 0 || $value == '1' ) {
                return true;
            }
            return $value ? true : false;
        }
        elseif( $dataType == 'float' ) {
            return (float) $value;
        }
        elseif( $dataType == 'DateTime' ) {
            // already a DateTime object
            if( is_a( $value , 'DateTime' ) ) {
                return $value;
            }
            if( $value ) {
                return new DateTime( $value );
            }
            return null;
        }
        return $value;
    }

}



