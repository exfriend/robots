<?php

namespace Exfriend\Robots;


class Regex
{

    public static function match( $pattern, $inputString, $modifiers = 'ims' )
    {
        if ( !preg_match( '~' . $pattern . '~' . $modifiers, $inputString, $matches ) )
        {
            return false;
        }
        return $matches;
    }

    public static function matchAll( $pattern, $inputString, $modifiers = 'ims' )
    {
        if ( !preg_match_all( '~' . $pattern . '~' . $modifiers, $inputString, $matches ) )
        {
            return false;
        }

        return $matches;
    }

    public static function between( $inputString, $start, $end, $only_inside = true )
    {
        $data = static::match( $start . '(.*?)' . $end, $inputString );
        if ( !$data )
        {
            return false;
        }

        if ( $only_inside )
        {
            $data = trim( $data[ 1 ] );
        }
        else
        {
            $data = trim( $data[ 0 ] );
        }


        return $data;
    }
}