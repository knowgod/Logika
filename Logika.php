#!/usr/bin/php

<?php
define("DEBUG_MODE", 0);

/**
 * Description of Logika
 *
 * @author arkadij
 */
class Logika
{

    const DigitsRange = 10;

    protected $_number;
    protected $_guessTry;
    protected $_guessResult;
    protected $_guessLog = array();
    protected $_analizeMatrix = array();
    protected $_anaMatrixDimensions = array();

    public function run($digits = 2)
    {
        $this->init($digits);
        $this->input();
        while (TRUE !== $this->compare()) {
            $this->input();
        }
        echo "\n\n======= YOU WIN !!! ========\n";
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
        $this->_initAnalizeMatrix(strlen($this->_number), self::DigitsRange);
    }

    protected function _updateAnaMatrixByZero($guess)
    {
//        $this->_log(array($guess, $this->_analizeMatrix));
        for ($i = 1; $i <= strlen($guess); ++$i) {
            unset($this->_analizeMatrix[$i][$guess[$i - 1]]);
        }
//        $this->_log(array($guess, $this->_analizeMatrix)); //!!!!
    }

    protected function _updateAnaMatrixByDigits($digits, $bInclude = TRUE)
    {
        $this->_log(array($digits));
        for ($i = 0; $i < $this->_anaMatrixDimensions['y']; ++$i) {
            $this->_log(array($digits, $i, strpos((string) $digits, (string) $i), $this->_analizeMatrix));
            if (!($bInclude && FALSE === strpos((string) $digits, (string) $i))) {
                continue;
            }
            foreach ($this->_analizeMatrix as $pos => &$aAvailable) {
                unset($aAvailable[$i]);
            }
        }
        $this->_log(array($digits, $this->_analizeMatrix)); //!!!!
    }

    protected function _initAnalizeMatrix($x, $y = 10)
    {
        $this->_anaMatrixDimensions = array('x' => $x, 'y' => $y);
        $sample = array_keys(array_fill(0, $y, ''));
        $this->_analizeMatrix = array_fill(1, $x, $sample);
    }

    protected function _getAnalizeOutput()
    {
        $output = $ruler = "\n" . str_repeat('=', $this->_anaMatrixDimensions['y'] + ($this->_anaMatrixDimensions['y'] - 1) * 3 + 5);
        foreach ($this->_analizeMatrix as $position => $aAllowedNums) {
            $output .= "\n$position => " . implode(' | ', $aAllowedNums);
        }
        return $output . $ruler . "\n";
    }

    public function input()
    {
        echo "\n\nYour guess > ";
        $stdin = trim(fgets(STDIN));
        $this->_guessTry = substr($stdin, 0, strlen($this->_number));
    }

    public function compare()
    {
        $original = array_flip(str_split($this->_number));
        $guess = array_flip(str_split($this->_guessTry));
        if (count($guess) != count($original)) {
            echo "\n No doubles allowed!!!";
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
                $this->_updateAnaMatrixByZero($this->_guessTry);
            }
            if (strlen($this->_number) == $correctNumber) {
                $this->_updateAnaMatrixByDigits($this->_guessTry);
            }
            if (0 == $correctNumber) {
                $this->_updateAnaMatrixByDigits($this->_guessTry, FALSE);
            }
            $this->_guessResult = "{$correctNumber}-{$correctPlace}";
            $try = (count($this->_guessLog) + 1) . ". {$this->_guessTry} :: {$this->_guessResult}";
            $this->_guessLog[] = $try;

            echo $this->_getAnalizeOutput();
            foreach ($this->_guessLog as $record) {
                echo "\n" . $record;
            }
        }
        return $original == $guess;
    }

    protected function _log($str)
    {
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            if (!isset($stack[1]['class'])) {
                $stack[1]['class'] = isset($stack[0]['file']) ? $stack[0]['file'] : 'noClass';
            }
            if (!isset($stack[1]['function'])) {
                $stack[1]['function'] = 'noFunc';
            }
            $before = "\nDEBUG: {$stack[0]['line']}. {$stack[1]['class']}::{$stack[1]['function']} :\n";
            fwrite(STDERR, $before . print_r($str));
        }
    }

}
?>

<?php
if (defined('LOGIKA_PHPUNIT_TESTING') && LOGIKA_PHPUNIT_TESTING) {
    die("/n---------- Expecting all tests performed here. End. -----------");
}

var_export($argv);
$l = new Logika();
$l->run(($argc > 1) ? $argv[1] : 2);