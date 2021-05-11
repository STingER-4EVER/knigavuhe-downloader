<?php

use Knigavuhe\Parser;

require_once __DIR__ . '/../../src/Utils.php';

class UtilsTest extends PHPUnit\Framework\TestCase
{
    /**
     * @param int $input
     * @param string $expected
     * @dataProvider provider_convertTimeToHuman
     */
    public function test_convertTimeToHuman( int $input, string $expected ) : void
    {
        $actual = \Knigavuhe\Utils::convertTimeToHuman( $input );
        self::assertEquals( $expected, $actual );
    }

    public function provider_convertTimeToHuman() : array
    {
        return [
            [
                'input'    => 1,
                'expected' => '1 sec'
            ],
            [
                'input'    => 45,
                'expected' => '45 sec'
            ],
            [
                'input'    => 59,
                'expected' => '59 sec'
            ],
            [
                'input'    => 60,
                'expected' => '1 min'
            ],
            [
                'input'    => 61,
                'expected' => '1 min 1 sec'
            ],
            [
                'input'    => 180,
                'expected' => '3 min'
            ],
            [
                'input'    => 1152,
                'expected' => '19 min 12 sec'
            ],
            [
                'input'    => 6092,
                'expected' => '1 hour 41 min 32 sec'
            ],
            [
                'input'    => 92492,
                'expected' => '1 day 1 hour 41 min 32 sec'
            ],
        ];
    }
}
