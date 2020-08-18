<?php

namespace Knigavuhe;

class BookObject
{
    protected $url;

    protected $name;

    protected $author;

    protected $reader;

    protected $time;

    protected $count_files;

    protected $files = [];

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl( $url )
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName( $name )
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor( $author )
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * @param string $reader
     */
    public function setReader( $reader )
    {
        $this->reader = $reader;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime( $time )
    {
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getCountFiles()
    {
        return $this->count_files;
    }

    /**
     * @param int $count_files
     */
    public function setCountFiles( $count_files )
    {
        $this->count_files = $count_files;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param array $files
     */
    public function setFiles( $files )
    {
        $this->files = $files;
    }
}