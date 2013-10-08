<?php

if (!defined('LOGIKA_PHPUNIT_TESTING'))
    define('LOGIKA_PHPUNIT_TESTING', 1);

require_once '../Logika.php';

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

    public function testGuessLike_x_x()
    {
        $guess = '6540';
        $assertRows = array(
            'guessLike_0_0' => array('123789', '123789', '123789', '123789'),
            'guessLike_4_x' => array('0456', '0456', '0456', '0456'),
            'guessLike_4_0' => array('045', '046', '056', '456'),
        );
        foreach ($assertRows as $method => $assertRow) {
            $instance = $this->_getInstance(4, 10);
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

}

?>
