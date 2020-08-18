<?php

namespace Knigavuhe;

class Application
{
    protected $book;

    protected $is_non_interactive;

    protected $user_agent;

    public function __construct()
    {
        Console::init();
        $this->book = Console::get( 'book', null, 'string' );
        $this->is_non_interactive = Console::get( 'non-interactive', null, 'bool' );
        $this->user_agent = Console::get( 'user-agent', DEFAULT_USER_AGENT, 'string' );
    }

    public function run()
    {
        if ( !$this->check( $error ) ) {
            $this->error( $error );
            $this->help();
            return;
        }

        $start = time();
        $http_client = new \GuzzleHttp\Client([ 'verify' => false ]);
        $book = $this->parse( $http_client );

        $this->infoStart( $book );

        if ( !$this->is_non_interactive ) {
            if ( Console::waitUserInput( 'Continue?', [ 'Y', 'N' ] ) === 'N' ) {
                return;
            }
        }

        $size = $this->download( $http_client, $book, $error );

        $this->infoEnd( $size, $start, time(), $error );
    }

    public function help()
    {
        $message = 'Usage: php app.php --book=... [OPTION]...' . "\n" .
                   'Download the required audiobook from knigavuhe.org, for offline listening.' . "\n" .
                   'Options list:' . "\n" .
                   '  --book=                book name from url' . "\n" .
                   '  --non-interactive      run without interactive shell' . "\n" .
                   '  --user-agent=          use your user agent' . "\n";

        echo $message;
    }

    protected function check( &$error )
    {
        if ( $this->book === '' ) {
            $error = 'book\'s name is empty';
            return false;
        }

        return true;
    }

    protected function error( $msg )
    {
        echo 'Error: ' . $msg . "\n";
    }

    protected function parse( $http_client )
    {
        return ( new Parser( $http_client, $this->user_agent ) )->get( sprintf( TARGET_URL, $this->book ) );
    }

    protected function infoStart( BookObject $book )
    {
        echo 'Source: ' . $book->getUrl() . "\n";
        echo 'Author: ' . $book->getAuthor() . "\n";
        echo 'Book name: ' . $book->getName() . "\n";
        echo 'Reader: ' . $book->getReader() . "\n";
        echo 'Time: ' . $book->getTime() . "\n";
        echo 'Count files: ' . $book->getCountFiles() . "\n";
    }

    protected function infoEnd( $size, $start, $end, $error )
    {
        echo 'Size: ' . $size . "\n";
        echo 'Time spent: ' . $this->convertTime( $start, $end ) . "\n";
        echo 'Error: ' . count( $error ) . "\n";
        if ( !empty( $error ) ) {
            echo 'Failed to download files:' . "\n";
            foreach ( $error as $e ) {
                echo "\t" . $e . "\n";
            }
        }
    }

    protected function download( $http_client, BookObject $book, &$error )
    {
        $dwn = new Download( $http_client, $this->user_agent );
        $dist = DOWNLOAD . $book->getAuthor() . ' - ' . $book->getName() . ' [' . $book->getReader() . ']' . '/';
        !is_dir( $dist ) && !mkdir( $dist, 0777, true ) && !is_dir( $dist );

        if ( !$this->is_non_interactive ) {
            echo 'Download: [0 / ' . $book->getCountFiles() . ']';
        }

        foreach ( $book->getFiles() as $i => $item ) {
            $dwn->run( $item[ 'url' ], $dist );

            if ( !$this->is_non_interactive ) {
                echo "\r";
                echo 'Download: [' . ( $i + 1 ) . ' / ' . $book->getCountFiles() . ']';
            }
        }

        if ( !$this->is_non_interactive ) {
            echo "\n";
        }

        $error = $dwn->getError();
        return $dwn->getSize();
    }

    protected function convertTime( $start, $end )
    {
        $work_time   = $end - $start;
        $work_day    = (int) ( $work_time / ( 60 * 60 * 24 ) );
        $work_hour   = (int) ( $work_time / ( 60 * 60 ) );
        $work_minute = (int) ( $work_time / 60 );
        $work_sec    = (int) ( $work_time % 60 );

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