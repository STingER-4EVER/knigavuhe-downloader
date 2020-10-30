<?php

namespace Knigavuhe;

class Utils
{
    /**
     * @param int $size
     * @return string
     */
    public static function convertIntToByteSize( int $size ) : string
    {
        static $size_type = [ 'b', 'Kb', 'Mb', 'Gb', 'Tb' ];
        $label = $size_type[ 0 ];

        foreach( $size_type as $label ) {
            if ( $size === 1024 ) {
                $size = 1;
                break;
            }

            if ( $size < 1024 ) {
                break;
            }

            $size /= 1024;
        }

        return round( $size, 2 )  . ' ' . $label;
    }

    /**
     * @param int $time
     * @return string
     */
    public static function convertTimeToHuman( int $time ) : string
    {
        $work_day    = (int) ( $time / ( 60 * 60 * 24 ) );
        $work_hour   = (int) ( $time / ( 60 * 60 ) );
        $work_minute = (int) ( $time / 60 );
        $work_sec    = (int) ( $time % 60 );

        $work_time_str = [];
        if ( $work_day > 0 ) {
            $work_time_str[] = $work_day . ' d';
        }

        if ( $work_hour > 0 ) {
            $work_time_str[] = $work_hour . ' h';
        }

        if ( $work_minute > 0 ) {
            $work_time_str[] = $work_minute . ' min';
        }

        $work_time_str[] = $work_sec . ' sec';

        return implode( ' ', $work_time_str );
    }
}