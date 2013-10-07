<?php

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
        define('LOGIKA_PHPUNIT_TESTING', TRUE);
        $instance = new Logika();
        $instance->init($length);
        return $instance;
    }

    public function testInput()
    {
        $instance = $this->_getInstance();
        $this->assertEquals('10', $instance->input('10'));
    }

}

?>
