<?php
/**
 * Created by PhpStorm.
 * User: arkadij
 * Date: 12.05.16
 * Time: 14:35
 */
namespace Arkuznet\Logika;

class AnalysisMatrix
{

    protected $_analizeMatrix = array();
    protected $_matrixDimensions = array();

    public function __construct($x, $y = 10) {
        $this->_matrixDimensions = array('x' => $x, 'y' => $y);
        $sample                  = array_keys(array_fill(0, $y, ''));
        $this->_analizeMatrix    = array_fill(1, $x, $sample);
    }

    /**
     * None of these numbers are present
     *
     * @param string $guess
     */
    public function guessLike_0_0($guess) {
        foreach ($this->_analizeMatrix as $pos => &$row) {
            for ($i = 0; $i < strlen($guess); ++$i) {
                unset($row[$guess[$i]]);
            }
        }
    }

    /**
     * Exclude numbers from its' places in matrix
     *
     * @param string $digits
     */
    public function guessLike_X_0($guess) {
//        Debug::log(array('digits' => $digits));
        for ($i = 0; $i < strlen($guess); ++$i) {
            unset($this->_analizeMatrix[$i + 1][$guess[$i]]);
        }
    }

    /**
     * All numbers guessed properly
     *
     * @param string $digits
     */
    public function guessLike_4_X($digits) {
        foreach ($this->_analizeMatrix as $pos => $row) {
            $newRow = str_split($digits);
            asort($newRow);
            $this->_analizeMatrix[$pos] = array_combine($newRow, $newRow);
        }
    }

    public function getTableOutput() {
        $output = $ruler = "\n" . str_repeat('=', $this->_matrixDimensions['y'] + ($this->_matrixDimensions['y'] - 1) * 3 + 5);
        foreach ($this->_analizeMatrix as $position => $aAllowedNums) {
            $output .= "\n$position => " . implode(' | ', $aAllowedNums);
        }
        return $output . $ruler . "\n";
    }

    public function test_getMatrix() {
        if (defined('LOGIKA_PHPUNIT_TESTING')) {
            $ret = array();
            foreach ($this->_analizeMatrix as $i => $row) {
                $ret[$i] = implode('', $row);
            }
            return $ret;
        }
        return false;
    }

}