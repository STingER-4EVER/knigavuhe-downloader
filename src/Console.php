<?php

namespace Knigavuhe;

class Console
{
    /**
     * @var array
     */
    protected static array $vars = [];

    /**
     * @var string[]
     */
    protected static array $variants = [
        '--', '-', ''
    ];

    public static function init() : void
    {
        self::parse();
    }

    /**
     * @param int|string $name
     * @param null|mixed $default
     * @param null|string $castToType
     * @return mixed
     */
    public static function get( $name, $default = null, $castToType = null )
    {
        $output = $default;

        if ( array_key_exists( $name, self::$vars ) ) {
            $output = self::$vars[ $name ];
        }

        if ( $castToType !== null ) {
            settype( $output, $castToType );
        }

        return $output;
    }

    /**
     *
     */
    protected static function parse() : void
    {
        $argv = isset( $_SERVER[ 'argv' ] ) ? $_SERVER[ 'argv' ] : [];

        // --param=1 --param 1 --param
        //  -param=1  -param 1  -param
        //   param=1   param
        if ( !empty( $argv ) && ( $c = count( $argv ) ) > 1 ) {
            for ( $i = 1; $i < $c; $i++ ) {
                foreach ( self::$variants as $prefix ) {
                    if ( $prefix ) {
                        if ( strncmp( $argv[ $i ], $prefix, strlen( $prefix ) ) === 0 ) {
                            self::parseArguments( $argv, $i, strlen( $prefix ) );
                            break;
                        }
                    } else {
                        self::parseArguments( $argv, $i, 0 );
                    }
                }
            }
        }
    }

    /**
     * @param array $argv
     * @param int $index
     * @param int $offset
     */
    protected static function parseArguments( array $argv, int $index, int $offset = 0 ) : void
    {
        $key   = $argv[ $index ];
        $value = true;

        if ( strpos( $argv[ $index ], '=' ) !== false ) {
            [ $key, $value ] = explode( '=', $argv[ $index ], 2 );
        } else {
            if ( $offset !== 0 && isset( $argv[ $index + 1 ] ) && strncmp( $argv[ $index + 1 ], '-', 1 ) !== 0 ) {
                $value = $argv[ ++$index ];
            }
        }

        if ( $offset === 0 && $value === true ) {
            self::$vars[] = $key;
        } else {
            self::$vars[ substr( $key, $offset ) ] = trim( $value, "\t\n\r\0\x0B\"'" );
        }
    }

    /**
     * @param string $message
     * @param array $options
     * @return string
     */
    public static function waitUserInput( string $message, array $options = [] ) : string
    {
        if ( !empty( $options ) ) {
            $message .= ' [' . implode( '/', $options ) . '] : ';
        }

        do {
            echo $message;
            flush();
            $answer = trim( fgets( STDIN ) );

            if ( empty( $options ) ) {
                break;
            }

            if ( in_array( $answer, $options, true ) ) {
                break;
            }
        } while ( 1 );

        return $answer;
    }
}