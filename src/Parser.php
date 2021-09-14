<?php

namespace Knigavuhe;

use DiDom\Document;
use Exception;

class Parser
{
    /**
     * @param string $html_page
     * @return BookObject
     */
    public function get( string $html_page ) : BookObject
    {
        $output = new BookObject();
        if ( $html_page !== '' ) {
            $document = new Document( $html_page );
            $output->setName( $this->getText( $document, '.book_title_name' ) );
            $output->setAuthor( $this->getTexts( $document, 'span[itemprop="author"] a' ) );
            $output->setReader( $this->getTexts( $document, '.book_title_elem a[href*="/reader/"]' ) );
            $output->setTime( $this->getTime( $document, '.book_cover_wrap .book_blue_block > div' ) );
            $output->setCountFiles( $this->getCount( $document, '.book_playlist_item_name' ) );
            $output->setFiles( $this->getFileList( $html_page ) );
            $output->setIsBlocked( (bool) $this->getCount( $document, '.book_playlist button.book_buy' ) );
        }

        return $output;
    }

    /**
     * @param Document $document
     * @param string $selector
     * @return string
     */
    protected function getText( Document $document, string $selector ) : string
    {
        try {
            $node = $document->first( $selector );
            if ( $node ) {
                return trim( $node->text() );
            }
        } catch ( Exception $e ) {}

        return '';
    }

    /**
     * @param Document $document
     * @param string $selector
     * @return string
     */
    protected function getTexts( Document $document, string $selector ) : string
    {
        try {
            $output = [];
            $node_list = $document->find( $selector );
            if ( !empty( $node_list ) ) {
                foreach ( $node_list as $node ) {
                    $output[] = trim( $node->text() );
                }

                return implode( ', ', $output );
            }
        } catch ( Exception $e ) {}

        return '';
    }

    /**
     * @param Document $document
     * @param string $selector
     * @return string
     */
    protected function getTime( Document $document, string $selector ) : string
    {
        try {
            $node = $document->first( $selector );
            if ( $node ) {
                $time_element = $node->child( 2 );
                return trim( $time_element !== null ? $time_element->text() : '' );
            }
        } catch ( Exception $e ) {}

        return '';
    }

    /**
     * @param Document $document
     * @param string $selector
     * @return int
     */
    protected function getCount( Document $document, string $selector ) : int
    {
        try {
            $node = $document->find( $selector );
            if ( $node ) {
                return count( $node );
            }
        } catch ( Exception $e ) {}

        return 0;
    }

    /**
     * @param string $page
     * @return array
     */
    protected function getFileList( string $page ) : array
    {
        $output = [];

        preg_match_all(
            '/var\splayer\s=\snew\sBookPlayer\(.*?(\[.+?\])/m',
            $page,
            $match,
            PREG_SET_ORDER
        );

        $json_book = $match[ 1 ][ 1 ] ?? $match[ 0 ][ 1 ];

        if ( $json_book ) {
            $json = @json_decode( $json_book, true );
            if ( $json !== null && json_last_error() === 0 ) {
                $output = $json;
            }
        }

        return $output;
    }
}
