<?php

namespace Knigavuhe;

class Parser
{
    protected $http_client;

    protected $user_agent;

    public function __construct( $http_client, $user_agent )
    {
        $this->http_client = $http_client;
        $this->user_agent = $user_agent;
    }

    public function get( $url )
    {
        $output = new BookObject();
        $output->setUrl( $url );

        $page = $this->getPage( $url );
        if ( $page !== '' ) {
            $document = new \DiDom\Document( $page );
            $output->setName( $this->getText( $document, '.book_title_name' ) );
            $output->setAuthor( $this->getText( $document, 'span[itemprop="author"] > a' ) );
            $output->setReader( $this->getText( $document, '.book_title_elem a[href*="/reader/"]' ) );
            $output->setTime( $this->getTime( $document, '.book_cover_wrap .book_blue_block' ) );
            $output->setCountFiles( $this->getCount( $document, '.book_playlist_item_name' ) );
            $output->setFiles( $this->getFileList( $page ) );
        }

        return $output;
    }

    protected function getPage( $url )
    {
        $response = $this->http_client->get( $url, [
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

    protected function getText( $document, $selector )
    {
        $node = $document->first( $selector );
        if ( $node ) {
            return trim( $node->text() );
        }

        return '';
    }

    protected function getTime( $document, $selector )
    {
        $node = $document->first( $selector );
        if ( $node ) {
            return trim( $node->child( 2 )->text() );
        }

        return '';
    }

    protected function getCount( $document, $selector )
    {
        $node = $document->find( $selector );
        if ( $node ) {
            return count( $node );
        }

        return 0;
    }

    protected function getFileList( $page )
    {
        $output = [];

        preg_match_all(
            '/var\splayer\s=\snew\sBookPlayer\(.*?(\[.+?\])/m',
            $page,
            $match,
            PREG_SET_ORDER
        );

        if ( isset( $match[ 1 ][ 1 ] ) ) {
            $output = json_decode( $match[ 1 ][ 1 ], true );
        }

        return $output;
    }
}