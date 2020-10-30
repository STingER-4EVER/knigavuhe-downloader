<?php

namespace Knigavuhe;

class Download
{
    protected $http_client;

    /**
     * @var string
     */
    protected string $user_agent;

    /**
     * @var array
     */
    protected array $error = [];

    /**
     * @var int
     */
    protected int $size = 0;

    /**
     * Download constructor.
     * @param $http_client
     * @param string $user_agent
     */
    public function __construct( $http_client, string $user_agent )
    {
        $this->http_client = $http_client;
        $this->user_agent = $user_agent;
    }

    /**
     * @param string $file
     * @param string $dist
     */
    public function run( string $file, string $dist ) : void
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

    /**
     * @return string
     */
    public function getSize() : string
    {
        return Utils::convertIntToByteSize( $this->size );
    }

    /**
     * @return array
     */
    public function getError() : array
    {
        return $this->error;
    }
}