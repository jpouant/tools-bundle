<?php

namespace Neirda24\Bundle\ToolsBundle\Utils;

class StringUtils
{
    /**
     * Return the $length first character from left to right in $str
     *
     * @param string  $str    The chain
     * @param integer $length Length wanted
     *
     * @return string
     */
    public function left($str, $length)
    {
        return mb_substr((string)$str, 0, (int)$length);
    }

    /**
     * Return the $length last character from right to left in $str
     *
     * @param string  $str    The chain
     * @param integer $length Length wanted
     *
     * @return string
     */
    public function right($str, $length)
    {
        return mb_substr((string)$str, -(int)$length);
    }

    /**
     * Return true if $haystack starts with $needle
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return boolean
     */
    public function startsWith($haystack, $needle)
    {
        return $needle === '' || mb_strpos((string)$haystack, (string)$needle) === 0;
    }

    /**
     * Return true if $haystack ends with $needle
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return boolean
     */
    public function endsWith($haystack, $needle)
    {
        return $needle === '' || mb_substr((string)$haystack, -mb_strlen((string)$needle)) === (string)$needle;
    }
}
