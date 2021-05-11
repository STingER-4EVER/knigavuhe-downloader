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
        $work_time_str = [];
        static $times = [
            'day'  => 60 * 60 * 24,
            'hour' => 60 * 60,
            'min'  => 60
        ];

        if ( $time < 60 ) {
            return $time . ' sec';
        }

        foreach ( $times as $post_fix => $num ) {
            if ( $time === 60 ) {
                $work_time_str[] = '1 min';
                $time = 0;
                break;
            }

            $result = (int) ( $time / $num );
            if ( $result > 0 ) {
                $work_time_str[] = $result . ' ' . $post_fix;
                $time -= ( $result * $num );
            }
        }

        if ( $time > 0 ) {
            $work_time_str[] = $time . ' sec';
        }

        return implode( ' ', $work_time_str );
    }
}
