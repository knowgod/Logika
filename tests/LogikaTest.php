<?php

if (!defined('LOGIKA_PHPUNIT_TESTING'))
    define('LOGIKA_PHPUNIT_TESTING', 1);

require_once '../Logika.php';

/**
 * Description of LogikaTest
 *
 * @author arkadij
 */
class LogikaTest extends PHPUnit_Framework_TestCase
{

    protected function _getInstance($length = 2)
    {
        $instance = new Logika();
        $instance->init($length);
        return $instance;
    }

    public function dataInput()
    {
        return array(
            array('10', '10', 2),
            array('08', '08', 2),
            array('10', '103', 2),
            array('55', '55', 2),
            array('159', '15964', 3),
            array('006', '0064', 3),
        );
    }

    /**
     * @dataProvider dataInput
     *
     * @param string $expect
     * @param string $param
     * @param int $inputLength
     */
    public function testInput($expect, $param, $inputLength)
    {
        $instance = $this->_getInstance($inputLength);
        $this->assertEquals($expect, $instance->input($param));
    }

    public function dataCompare()
    {
        $instance = $this->_getInstance(4);
        $secretNumber = $instance->test_getNumber();
        return array(
            array($instance, 'False', strrev($secretNumber)),
            array($instance, 'True', $secretNumber),
            array($instance, (('6540' == $secretNumber) ? 'True' : 'False'), '6540'),
            array($instance, (('3186' == $secretNumber) ? 'True' : 'False'), '3186456'),
        );
    }

    /**
     * @dataProvider dataCompare
     *
     * @param Logika $instance
     * @param string $assertMethod
     * @param string $guessNumber
     */
    public function testCompare($instance, $assertMethod, $guessNumber)
    {
        $method = 'assert' . $assertMethod;
        $instance->input($guessNumber);
        $this->$method($instance->compare());
    }

    public function testOutput_01()
    {
        $instance = $this->_getInstance(3);
        $instance->input('333');
        ob_start();
        $instance->compare();
        $ob = ob_get_flush();
        $this->assertEquals(Logika::S_NoDoublesAllowed, $ob);
    }

    public function testOutput_02()
    {
        $instance = $this->_getInstance(3);
        $secretNumber = $instance->test_getNumber();
        $anaMatrix = new AnalysisMatrix(3, 10);
        $anaMatrix->guessLike_4_X($secretNumber);
        $str = <<<STR
{$anaMatrix->getTableOutput()}
1. {$secretNumber} :: 3-3
STR;
        $instance->input($secretNumber);
        ob_start();
        $instance->compare();
        $ob = ob_get_flush();
        $this->assertEquals($str, $ob);
    }

    public function testOutput_03()
    {
        $instance = $this->_getInstance(3);
        $secretNumber = $instance->test_getNumber();
        ob_start();
        $instance->input($secretNumber);
        $ob = ob_get_flush();
        $this->assertEquals(Logika::S_YourGuess, $ob);
    }

}

?>
