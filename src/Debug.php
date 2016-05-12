<?php
/**
 * Created by PhpStorm.
 * User: arkadij
 * Date: 12.05.16
 * Time: 14:36
 */
namespace Arkuznet\Logika;

/**
 * Debugging functions gathered here
 */
class Debug
{

    protected static function _isEnabled() {
        return (defined('LOGIKA_PHPUNIT_TESTING') && LOGIKA_PHPUNIT_TESTING) || (defined('LOGIKA_DEBUG_MODE') && LOGIKA_DEBUG_MODE);
    }

    /**
     *
     * @param mixed $str
     * @param int $shift
     */
    public static function log($str, $shift = 0) {
        if (self::_isEnabled()) {
            $stack  = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $before = "\nDEBUG: {$stack[0 + $shift]['line']}. {$stack[1 + $shift]['class']}::{$stack[1 + $shift]['function']} :\n";
            fwrite(STDOUT, $before . print_r($str, 1));
        }
    }

}
