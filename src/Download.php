<?php

namespace Knigavuhe;

class Download
{
    protected $http_client;

    protected $user_agent;

    protected $error = [];

    protected $size = 0;

    public function __construct( $http_client, $user_agent )
    {
        $this->http_client = $http_client;
        $this->user_agent = $user_agent;
    }

    public function run( $file, $dist )
    {
        $path = parse_url( $file, PHP_URL_PATH );
        $file_name = basename( $path );
        $mp3 = $this->http_client->get( $file, [
            'headers'=> [
                'User-Agent' => $this->user_agent,
                'Accept-Encoding' => 'gzip'
            ],
            'decode_content' => 'gzip'
        ] );

        if ( $mp3->getStatusCode() === 200 ) {
            $this->size += @file_put_contents( $dist . $file_name, ( (string) $mp3->getBody() ) );
        } else {
            $this->error[] = $file_name;
        }
    }

    public function getSize()
    {
        $size = $this->size;

        foreach( [ 'b', 'Kb', 'Mb', 'Gb', 'Tb' ] as $label ) {
            if ( $size === 1024 ) {
                $size = 1 . ' ' . $label;
                break;
            }

            if ( $size < 1024 ) {
                $size = round( $size, 2 ) . ' ' . $label;
                break;
            }

            $size /= 1024;
        }

        return $size;
    }

    public function getError()
    {
        return $this->error;
    }
}