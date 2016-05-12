<?php

if (!defined('LOGIKA_PHPUNIT_TESTING'))
    define('LOGIKA_PHPUNIT_TESTING', 1);

use Arkuznet\Logika\AnalysisMatrix;
use Arkuznet\Logika\Debug;

/**
 * Description of LogikaTest
 *
 * @author arkadij
 */
class AnalysisMatrixTest extends PHPUnit_Framework_TestCase
{

    protected function _getInstance($x, $y)
    {
        $instance = new AnalysisMatrix($x, $y);
        return $instance;
    }

    public function dataGuessLike_x_x()
    {
        return array(
            array('guessLike_0_0', '6540', array('123789', '123789', '123789', '123789')),
            array('guessLike_4_X', '6540', array('0456', '0456', '0456', '0456')),
            array('guessLike_X_0', '6540', array('012345789', '012346789', '012356789', '123456789')),
        );
    }

    /**
     * @dataProvider dataGuessLike_x_x
     *
     * @param string $method which method to test
     * @param string $guess guess number
     * @param array $assertRow expected matrix state
     * @param int $x secret number base
     */
    public function testGuessLike_x_x($method, $guess, array $assertRow, $x = 10)
    {
        $instance = $this->_getInstance(strlen($guess), $x);
        $instance->$method($guess);
        $matrix = $instance->test_getMatrix();
        Debug::log(array('--------------------------' => $method, '$matrix' => $matrix, '$assertRow' => $assertRow));
        $this->assertCount(4, $matrix);
        foreach ($matrix as $i => $row) {
            Debug::log(array(' $i' => $i, '$row' => $row, '$assertRow  ' => $assertRow));
            $this->assertEquals($assertRow[$i - 1], $row);
        }
    }

}
