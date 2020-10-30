<?php

namespace Knigavuhe;

class BookObject
{
    protected string $url;

    protected string $name;

    protected string $author;

    protected string $reader;

    protected string $time;

    protected int $count_files;

    protected array $files = [];

    protected bool $is_blocked = false;

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl( $url ) : void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName( $name ) : void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAuthor() : string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor( $author ) : void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getReader() : string
    {
        return $this->reader;
    }

    /**
     * @param string $reader
     */
    public function setReader( $reader ) : void
    {
        $this->reader = $reader;
    }

    /**
     * @return string
     */
    public function getTime() : string
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime( $time ) : void
    {
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getCountFiles() : int
    {
        return $this->count_files;
    }

    /**
     * @param int $count_files
     */
    public function setCountFiles( $count_files ) : void
    {
        $this->count_files = $count_files;
    }

    /**
     * @return array
     */
    public function getFiles() : array
    {
        return $this->files;
    }

    /**
     * @param array $files
     */
    public function setFiles( $files ) : void
    {
        $this->files = $files;
    }

    /**
     * @return bool
     */
    public function isIsBlocked() : bool
    {
        return $this->is_blocked;
    }

    /**
     * @param bool $is_blocked
     */
    public function setIsBlocked( bool $is_blocked ) : void
    {
        $this->is_blocked = $is_blocked;
    }
}