<?php

define('LOGIKA_PHPUNIT_TESTING', 1);

require_once '../Logika.php';

/**
 * Description of LogikaTest
 *
 * @author arkadij
 */
class AnalysisMatrixTest extends PHPUnit_Framework_TestCase
{

    protected function _getInstance($length = 2)
    {
        $instance = new AnalysisMatrix();
        $instance->init(4, 10);
        return $instance;
    }

}

?>
