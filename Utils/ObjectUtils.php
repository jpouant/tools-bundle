<?php

namespace Neirda24\Bundle\ToolsBundle\Utils;

class ObjectUtils
{
    /**
     * Retrieve the caller of a function.
     *
     * @param int $depth To get the parent use 1. To get its parent use 2, ..., ...
     *
     * @return mixed
     */
    public static function retrieveCaller($depth = 1)
    {
        // 1 Contains ContentUtils
        // 2 Contains The caller of this function
        // 3 Contains what you want

        $debug = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2 + $depth);
        $backtrace = array_pop($debug);
        $caller = $backtrace['object'];

        return $caller;
    }
}
