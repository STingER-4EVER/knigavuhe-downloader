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

    // ==============================================================

    /**
     * @param int $input
     * @param string $expected
     * @dataProvider provider_convertIntToByteSize
     */
    public function test_convertIntToByteSize( int $input, string $expected ) : void
    {
        $actual = \Knigavuhe\Utils::convertIntToByteSize( $input );
        self::assertEquals( $expected, $actual );
    }

    public function provider_convertIntToByteSize() : array
    {
        return [
            [
                'input'    => 1,
                'expected' => '1 b'
            ],
            [
                'input'    => 10,
                'expected' => '10 b'
            ],
            [
                'input'    => 1024,
                'expected' => '1 Kb'
            ],
            [
                'input'    => 1280,
                'expected' => '1.25 Kb'
            ],
            [
                'input'    => 1048576,
                'expected' => '1 Mb'
            ],
            [
                'input'    => 1051034,
                'expected' => '1 Mb'
            ],
            [
                'input'    => 1518518,
                'expected' => '1.45 Mb'
            ],
            [
                'input'    => 2935114,
                'expected' => '2.8 Mb'
            ],
            [
                'input'    => 1581717814,
                'expected' => '1.47 Gb'
            ],
            [
                'input'    => 6413077211,
                'expected' => '5.97 Gb'
            ],
        ];
    }
}
