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

    public function testUpdateByDigits()
    {
        $instance = $this->_getInstance(4, 10);

        $instance->guessedDigitsCorrect('6540');
        $matrix = $instance->test_getMatrix();
        Debug::log($matrix);
        $this->assertCount(4, $matrix);
        foreach ($matrix as $row) {
            $this->assertEquals('0456', $row);
        }
    }

}

?>
