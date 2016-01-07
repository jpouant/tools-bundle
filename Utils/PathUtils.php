<?php

namespace Neirda24\Bundle\ToolsBundle\Utils;

class PathUtils
{
    /**
     * @param string $haystack
     *
     * @return string
     */
    public static function cleanHomeVariable($haystack)
    {
        return preg_replace('#^(\$HOME|~)(/|$)#', rtrim(getenv('HOME') ?: getenv('USERPROFILE'), '/\\') . '/', $haystack);
    }
}
