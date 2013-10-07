<?php

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

    public function testInput()
    {
        $instance = $this->_getInstance(2);
        $this->assertEquals('10', $instance->input('10'));
        $this->assertEquals('08', $instance->input('08'));
        $this->assertEquals('10', $instance->input('103'));
        $this->assertEquals('55', $instance->input('55'));
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
