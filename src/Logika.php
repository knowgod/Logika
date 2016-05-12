<?php

namespace Arkuznet\Logika;

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
        $this->_analysisMatrix = new AnalysisMatrix(strlen($this->_number), self::DigitsRange);
        return $this->_number;
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
//        Debug::log(array('$original' => $original, '$guess' => $guess));
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
            if (0 == $correctNumber) {
                $this->_analysisMatrix->guessLike_0_0($this->_guessTry);
            } else {
                if (0 == $correctPlace) {
                    $this->_analysisMatrix->guessLike_X_0($this->_guessTry);
                }
                if (strlen($this->_number) == $correctNumber) {
                    $this->_analysisMatrix->guessLike_4_X($this->_guessTry);
                }
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


?>