<?php

namespace Neirda24\Bundle\ToolsBundle\Utils;

class ObjectUtils
{
    /**
     * Removes whitespace from a PHP source string while preserving line numbers.
     *
     * @return mixed
     */
    public static function retrieveCaller()
    {
        // 1 Contains ContentUtils
        // 2 Contains The caller of this function
        // 3 Contains what you want

        $debug = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3);
        $backtrace = array_pop($debug);
        $caller = $backtrace['object'];

        return $caller;
    }
}
