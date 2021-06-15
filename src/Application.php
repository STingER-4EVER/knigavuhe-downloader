<?php

namespace Knigavuhe;

class Application
{
    /**
     * @var string
     */
    protected $book;

    /**
     * @var bool
     */
    protected $is_non_interactive;

    /**
     * @var string
     */
    protected $user_agent;

    public function __construct()
    {
        Console::init();
        $this->book = Console::get( 'book', null, 'string' );
        $this->is_non_interactive = Console::get( 'non-interactive', null, 'bool' );
        $this->user_agent = Console::get( 'user-agent', DEFAULT_USER_AGENT, 'string' );
    }

    public function run() : void
    {
        if ( !$this->check( $error ) ) {
            $this->error( $error );
            $this->help();
            return;
        }

        $start = time();
        $http_client = new \GuzzleHttp\Client( [ 'verify' => false ] );

        $page = $this->getPage( $http_client, sprintf( TARGET_URL, $this->book ) );
        $book = $this->parse( $page );

        $this->infoStart( $book );

        if ( $book->isIsBlocked() ) {
            return;
        }

        if ( !$this->is_non_interactive ) {
            if ( Console::waitUserInput( 'Continue?', [ 'Y', 'N' ] ) === 'N' ) {
                return;
            }
        }

        $size = $this->download( $http_client, $book, $error );

        $this->infoEnd( $size, $start, time(), $error );
    }

    public function help() : void
    {
        $message = 'Usage: php app.php --book=... [OPTION]...' . "\n" .
                   'Download the required audiobook from knigavuhe.org, for offline listening.' . "\n" .
                   'Options list:' . "\n" .
                   '  --book=                book name from url' . "\n" .
                   '  --non-interactive      run without interactive shell' . "\n" .
                   '  --user-agent=          use your user agent' . "\n";

        echo $message;
    }

    /**
     * @param string $error
     * @return string
     */
    protected function check( &$error ) : string
    {
        if ( $this->book === '' ) {
            $error = 'book\'s name is empty';
            return false;
        }

        return true;
    }

    /**
     * @param string $msg
     */
    protected function error( string $msg ) : void
    {
        echo 'Error: ' . $msg . "\n";
    }

    /**
     * @param string $html_page
     * @return BookObject
     */
    protected function parse( string $html_page ) : BookObject
    {
        $book = ( new Parser() )->get( $html_page );
        $book->setUrl( sprintf( TARGET_URL, $this->book ) );

        return $book;
    }

    /**
     * @param BookObject $book
     */
    protected function infoStart( BookObject $book ) : void
    {
        echo 'Source: ' . $book->getUrl() . "\n";
        echo 'Author: ' . $book->getAuthor() . "\n";
        echo 'Book name: ' . $book->getName() . "\n";
        echo 'Reader: ' . $book->getReader() . "\n";
        echo 'Time: ' . $book->getTime() . "\n";
        echo 'Count files: ' . $book->getCountFiles() . "\n";
        echo 'Status: ' . ( $book->isIsBlocked() ? 'blocked' : 'allowed' ) . "\n";
    }

    /**
     * @param string $size
     * @param int $start
     * @param int $end
     * @param array $error
     */
    protected function infoEnd( string $size, int $start, int $end, array $error ) : void
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

    /**
     * @param $http_client
     * @param string $url
     * @return string
     */
    protected function getPage( $http_client, string $url ) : string
    {
        $response = $http_client->get( $url, [
            'headers' => [
                'User-Agent' => $this->user_agent,
                'Accept-Encoding' => 'gzip'
            ],
            'decode_content' => 'gzip'
        ] );

        if ( $response->getStatusCode() === 200 ) {
            return (string) $response->getBody();
        }

        return '';
    }

    /**
     * @param $http_client
     * @param BookObject $book
     * @param $error
     * @return string
     */
    protected function download( $http_client, BookObject $book, &$error ) : string
    {
        $dwn = new Download( $http_client, $this->user_agent );
        $dist = DOWNLOAD . $book->getAuthor() . ' - ' . $book->getName() . ' [' . $book->getReader() . ']' . '/';
        !is_dir( $dist ) && !mkdir( $dist, 0777, true ) && !is_dir( $dist );

        if ( !$this->is_non_interactive ) {
            echo 'Download: [0 / ' . $book->getCountFiles() . ']';
        }

        foreach ( $book->getFiles() as $i => $item ) {
            $progress = static function ( $downloadTotal,
                                          $downloadedBytes,
                                          $uploadTotal,
                                          $uploadedBytes ) use ( $i, $book ) {

                static $last_percent = 0;
                $count_num = strlen( (string) $book->getCountFiles() );
                $percent = ( $downloadedBytes === 0 || $downloadTotal === 0 )
                    ? 0
                    : ( $downloadedBytes / $downloadTotal * 100 );

                if ( $percent % 5 !== 0 || $last_percent === (int) $percent ) {
                    return;
                }
                $last_percent = (int) $percent;

                $pattern = 'Download: [% ' . $count_num . 's/%s] [%-10s] [% 3s%%] [% 9s/% 9s]';
                $message = sprintf(
                    $pattern,
                    ( $i + 1 ),
                    $book->getCountFiles(),
                    str_repeat( '=', (int) ceil( $percent ? ( $percent / 10 ) : 0 ) ),
                    (int) ceil( $percent ),
                    $downloadedBytes ? Utils::convertIntToByteSize( $downloadedBytes, '' ) : '?',
                    $downloadTotal ? Utils::convertIntToByteSize( $downloadTotal, '' ) : '?'
                );

                echo "\r";
                echo $message;
            };

            $dwn->run( $item[ 'url' ], $dist, $this->is_non_interactive ? null : $progress );
        }

        if ( !$this->is_non_interactive ) {
            echo "\r";
            echo 'Download: [' . $book->getCountFiles() . ' / ' . $book->getCountFiles() . ']' . str_repeat( ' ', 50 );
            echo "\n";
        }

        $error = $dwn->getError();
        return $dwn->getSize();
    }

    /**
     * @param int $start
     * @param int $end
     * @return string
     */
    protected function convertTime( int $start, int $end ) : string
    {
        return Utils::convertTimeToHuman( $end - $start );
    }
}
