<?php

namespace Neirda24\Bundle\ToolsBundle\Utils;

class ExcelUtils
{
    /**
     * Convert a number to a excel column with letters
     *
     * @example <code>
     *          numberToColumnLetter(1) => A
     *          numberToColumnLetter(2) => B
     *          numberToColumnLetter(27) => AA
     *          numberToColumnLetter(28) => AB
     *          numberToColumnLetter(14558) => UMX
     *          </code>
     *
     * @param  integer $number The number of the column
     *
     * @return string The column with letters
     */
    public function numberToColumnLetter(int $number): string
    {
        for ($r = ''; $number >= 0; $number = (int)($number / 26) - 1) {
            $r = chr($number % 26 + 0x41) . $r;
        }

        return $r;
    }
}
