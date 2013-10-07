#!/usr/bin/php

<?php
define("LOGIKA_DEBUG_MODE", 0);

/**
 * Logika game itself
 *
 * @author arkadij
 */
class Logika
{

    const DigitsRange = 10;
    /**
     * Literal strings:
     */
    const S_NoDoublesAllowed = "\n No doubles allowed!!!";
    const S_YourGuess = "\n\nYour guess > ";
    const S_YouWin = "\n\n======= YOU WIN !!! ========\n";

    protected $_number;
    protected $_guessTry;
    protected $_guessResult;
    protected $_guessLog = array();

    /**
     * @var AnalysisMatrix
     */
    protected $_analysisMatrix;

    public function run($digits = 2)
    {
        $this->init($digits);
        $this->input();
        while (TRUE !== $this->compare()) {
            $this->input();
        }
        echo self::S_YouWin;
    }

    public function init($digits)
    {
        $numbers = array();
        for ($i = 0; $i < $digits; ++$i) {
            $unique = FALSE;
            while ($unique != TRUE) {
                $numbers[$i] = rand(0, self::DigitsRange - 1);
                if ($i > 0) {
                    for ($j = 0; $j < $i; ++$j) {
                        $this->_log("{$i}/{$digits}; j={$j}; >>> " . var_export($numbers, 1));
                        if ($numbers[$j] == $numbers[$i]) {
                            $unique = FALSE;
                            break;
                        } else {
                            $unique = TRUE;
                        }
                    }
                } else {
                    $unique = TRUE;
                }
            }
        }
        $this->_number = implode('', $numbers);
//        $this->_log($this->_number);
        $this->_analysisMatrix = new AnalysisMatrix(strlen($this->_number), self::DigitsRange);
    }

    public function test_getNumber()
    {
        if (defined('LOGIKA_PHPUNIT_TESTING')) {
            return $this->_number;
        }
    }

    public function input($inputString = NULL)
    {
        echo self::S_YourGuess;
        $this->_log($inputString);
        if (is_null($inputString)) {
            $inputString = trim(fgets(STDIN));
        }
        $this->_guessTry = substr($inputString, 0, strlen($this->_number));
        return $this->_guessTry;
    }

    public function compare()
    {
        $original = array_flip(str_split($this->_number));
        $guess = array_flip(str_split($this->_guessTry));
        Debug::log(array($original, $guess));
        if (count($guess) != count($original)) {
            echo self::S_NoDoublesAllowed;
        } else {
            $correctNumber = 0;
            $correctPlace = 0;
            foreach ($original as $_original => $i) {
                if (isset($guess[$_original])) {
                    $correctNumber++;
                    if ($guess[$_original] == $i) {
                        $correctPlace++;
                    }
                }
            }
            if (0 == $correctPlace) {
                $this->_analysisMatrix->guessLike_0_0($this->_guessTry);
            }
            if (strlen($this->_number) == $correctNumber) {
                $this->_analysisMatrix->guessLike_4_x($this->_guessTry);
            }
            if (0 == $correctNumber) {
                $this->_analysisMatrix->guessLike_4_0($this->_guessTry);
            }
            $this->_guessResult = "{$correctNumber}-{$correctPlace}";
            $try = (count($this->_guessLog) + 1) . ". {$this->_guessTry} :: {$this->_guessResult}";
            $this->_guessLog[] = $try;

            echo $this->_analysisMatrix->getTableOutput();
            foreach ($this->_guessLog as $record) {
                echo "\n" . $record;
            }
        }
        return $original == $guess;
    }

    /**
     * Legacy method
     *
     * @param mixed $str
     */
    protected function _log($str)
    {
        Debug::log($str, 1);
    }

}

class AnalysisMatrix
{

    protected $_analizeMatrix = array();
    protected $_anaMatrixDimensions = array();

    public function __construct($x, $y = 10)
    {
        $this->_anaMatrixDimensions = array('x' => $x, 'y' => $y);
        $sample = array_keys(array_fill(0, $y, ''));
        $this->_analizeMatrix = array_fill(1, $x, $sample);
    }

    /**
     * Exclude numbers from its' places in matrix
     * @param string $guess
     */
    public function guessLike_0_0($guess)
    {
//        Debug::log(array($guess, $this->_analizeMatrix));
        for ($i = 1; $i <= strlen($guess); ++$i) {
            unset($this->_analizeMatrix[$i][$guess[$i - 1]]);
        }
        Debug::log(array('guess' => $guess, 'Matrix' => $this->_analizeMatrix));
    }

    /**
     * None of these numbers are present
     * @param string $digits
     */
    public function guessLike_4_0($digits)
    {
        $this->_updateByDigits($digits, FALSE);
    }

    /**
     * All numbers guessed properly
     * @param string $digits
     */
    public function guessLike_4_x($digits)
    {
        $this->_updateByDigits($digits);
    }

    /**
     * Set these digits as present in matrix or not - depends on $bInclude
     *
     * @param string $digits
     * @param bool $bInclude
     */
    protected function _updateByDigits($digits, $bInclude = TRUE)
    {
//        Debug::log(array('digits' => $digits));
        for ($i = 0; $i < $this->_anaMatrixDimensions['y']; ++$i) {
//            Debug::log(array('digits' => $digits, 'i' => $i, strpos((string) $digits, (string) $i), 'Matrix' => $this->_analizeMatrix));
            if (!($bInclude && FALSE === strpos((string) $digits, (string) $i))) {
                continue;
            }
            foreach ($this->_analizeMatrix as $pos => &$aAvailable) {
                unset($aAvailable[$i]);
            }
        }
        Debug::log(array('digits' => $digits, 'Matrix' => $this->_analizeMatrix));
    }

    public function getTableOutput()
    {
        $output = $ruler = "\n" . str_repeat('=', $this->_anaMatrixDimensions['y'] + ($this->_anaMatrixDimensions['y'] - 1) * 3 + 5);
        foreach ($this->_analizeMatrix as $position => $aAllowedNums) {
            $output .= "\n$position => " . implode(' | ', $aAllowedNums);
        }
        return $output . $ruler . "\n";
    }

    public function test_getMatrix()
    {
        if (defined('LOGIKA_PHPUNIT_TESTING')) {
            $ret = array();
            foreach ($this->_analizeMatrix as $i => $row) {
                $ret[$i] = implode('', $row);
            }
            return $ret;
        }
        return FALSE;
    }

}

/**
 * Debugging functions gathered here
 */
class Debug
{

    protected static function _isEnabled()
    {
        return (defined('LOGIKA_PHPUNIT_TESTING') && LOGIKA_PHPUNIT_TESTING) || (defined('LOGIKA_DEBUG_MODE') && LOGIKA_DEBUG_MODE);
    }

    /**
     *
     * @param mixed $str
     * @param int $shift
     */
    public static function log($str, $shift = 0)
    {
        if (self::_isEnabled()) {
            $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            if (!isset($stack[1 + $shift]['class'])) {
                $stack[1 + $shift]['class'] = isset($stack[0 + $shift]['file']) ? $stack[0 + $shift]['file'] : 'noClass';
            }
            if (!isset($stack[1]['function'])) {
                $stack[1 + $shift]['function'] = 'noFunc';
            }
            $before = "\nDEBUG: {$stack[0 + $shift]['line']}. {$stack[1 + $shift]['class']}::{$stack[1 + $shift]['function']} :\n";
            fwrite(STDOUT, $before . print_r($str, 1));
        }
    }

}
?>

<?php
if (defined('LOGIKA_PHPUNIT_TESTING') && LOGIKA_PHPUNIT_TESTING) {
    echo "\n---------- Starting tests here. Required class plugged in. -----------\n\n";
} else {
    var_export($argv);
    $l = new Logika();
    $l->run(($argc > 1) ? $argv[1] : 2);
}
