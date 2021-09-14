<?php

use Knigavuhe\Parser;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/Parser.php';
require_once __DIR__ . '/../../src/BookObject.php';

class ParserTest extends PHPUnit\Framework\TestCase
{
    public function testGet_1() : void
    {
        $class = new Parser();
        $html_page = file_get_contents( __DIR__ . '/../data/page-01.html' );
        $actual = $class->get( $html_page );

        self::assertEquals( '09:40:03', $actual->getTime() );
        self::assertEquals( 'Стрелок', $actual->getName() );
        self::assertEquals( 'Стивен Кинг', $actual->getAuthor() );
        self::assertEquals( 'Игорь Князев', $actual->getReader() );
        self::assertEquals( 1, $actual->getCountFiles() );
        self::assertEquals( true, $actual->isIsBlocked() );
    }

    public function testGet_2() : void
    {
        $class = new Parser();
        $html_page = file_get_contents( __DIR__ . '/../data/page-02.html' );
        $actual = $class->get( $html_page );

        self::assertEquals( '31:20:45', $actual->getTime() );
        self::assertEquals( 'Чёрный дом', $actual->getName() );
        self::assertEquals( 'Стивен Кинг, Питер Страуб', $actual->getAuthor() );
        self::assertEquals( 'BIGBAG', $actual->getReader() );
        self::assertEquals( 75, $actual->getCountFiles() );
        self::assertEquals( false, $actual->isIsBlocked() );
    }

    public function testGet_3() : void
    {
        $class = new Parser();
        $html_page = file_get_contents( __DIR__ . '/../data/page-03.html' );
        $actual = $class->get( $html_page );

        self::assertEquals( '10:09:11', $actual->getTime() );
        self::assertEquals( 'Куджо', $actual->getName() );
        self::assertEquals( 'Стивен Кинг', $actual->getAuthor() );
        self::assertEquals( 'BIGBAG', $actual->getReader() );
        self::assertEquals( 23, $actual->getCountFiles() );
        self::assertEquals( false, $actual->isIsBlocked() );
    }

    public function testGet_4() : void
    {
        $class = new Parser();
        $html_page = file_get_contents( __DIR__ . '/../data/page-04.html' );
        $actual = $class->get( $html_page );

        self::assertEquals( '05:49:15', $actual->getTime() );
        self::assertEquals( 'Фауст', $actual->getName() );
        self::assertEquals( 'Иоганн Вольфганг фон Гёте', $actual->getAuthor() );
        self::assertEquals( 'Артем Карапетян, Сергей Чонишвили, Ирина Киреева, Валерий Баринов', $actual->getReader() );
        self::assertEquals( 18, $actual->getCountFiles() );
        self::assertEquals( false, $actual->isIsBlocked() );
    }

    public function testGet_5() : void
    {
        $class = new Parser();
        $html_page = file_get_contents( __DIR__ . '/../data/page-05.html' );
        $actual = $class->get( $html_page );

        self::assertEquals( '31:20:45', $actual->getTime() );
        self::assertEquals( 'Чёрный дом', $actual->getName() );
        self::assertEquals( 'Стивен Кинг, Питер Страуб', $actual->getAuthor() );
        self::assertEquals( 'BIGBAG', $actual->getReader() );
        self::assertEquals( 75, $actual->getCountFiles() );
        self::assertEquals( false, $actual->isIsBlocked() );
    }
}
