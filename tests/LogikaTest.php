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

    public function dataInput() {
        return array(
            array('10', '10'),
            array('08', '08'),
            array('10', '103'),
            array('55', '55'),
        );
    }

    /**
     * @dataProvider dataInput
     */
    public function testInput($expect, $param)
    {
        $instance = $this->_getInstance(2);
        $this->assertEquals($expect, $instance->input($param));
    }

    public function testCompare()
    {
        $instance = $this->_getInstance(4);
        $secretNumber = $instance->test_getNumber();

        $instance->input(strrev($secretNumber));
        $this->assertFalse($instance->compare());

        $instance->input($secretNumber);
        $this->assertTrue($instance->compare());

        $guessNumber = '6540';
        $instance->input($guessNumber);
        Debug::log(array($guessNumber, $secretNumber));
        if ($guessNumber == $secretNumber) {
            $this->assertTrue($instance->compare());
        } else {
            $this->assertFalse($instance->compare());
        }
    }

}

?>
