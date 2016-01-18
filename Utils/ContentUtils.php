<?php

namespace Neirda24\Bundle\ToolsBundle\Utils;

class ContentUtils
{
    /**
     * @param array  $array
     * @param string $eol
     * @param int    $level
     *
     * @return string
     */
    public static function arrayToString(array $array, $eol = '', $level = 0)
    {
        $result = '';
        $spacesIndent = $spacesStart = '';
        for ($i = 0; $i <= $level; $i++) {
            if ($i > 0) {
                $spacesStart .= '    ';
            }
            $spacesIndent .= '    ';
        }
        foreach ($array as $key => $value) {
            if (!is_int($key)) {
                $key = '"' . str_replace('\\', '\\\\', $key) . '"';
            }
            if (is_array($value)) {
                $stringValue = static::arrayToString($value, $eol, ($level + 1));
            } else {
                $stringValue = '"' . str_replace('\\', '\\\\', $value) . '"';
            }
            $result .= "\n";
            $result .= sprintf('%s%s => %s%s', $spacesIndent, $key, $stringValue, $eol);
        }
        $result .= "\n";
        $result .= $spacesStart;

        return $result;
    }
}
