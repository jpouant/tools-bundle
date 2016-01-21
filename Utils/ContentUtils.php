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
        $result       = '';
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

    /**
     * Removes whitespace from a PHP source string while preserving line numbers.
     *
     * @param  string $source A PHP string
     *
     * @return string The PHP string with the whitespace removed
     */
    public static function stripWhitespace($source)
    {
        if (!function_exists('token_get_all')) {
            return $source;
        }

        $output = '';
        foreach (token_get_all($source) as $token) {
            if (is_string($token)) {
                $output .= $token;
            } elseif (in_array($token[0], [T_COMMENT, T_DOC_COMMENT])) {
                $output .= str_repeat("\n", substr_count($token[1], "\n"));
            } elseif (T_WHITESPACE === $token[0]) {
                // reduce wide spaces
                $whitespace = preg_replace('{[ \t]+}', ' ', $token[1]);
                // normalize newlines to \n
                $whitespace = preg_replace('{(?:\r\n|\r|\n)}', "\n", $whitespace);
                // trim leading spaces
                $whitespace = preg_replace('{\n +}', "\n", $whitespace);
                $output .= $whitespace;
            } else {
                $output .= $token[1];
            }
        }

        return $output;
    }
}
