<?php

namespace Neirda24\Bundle\ToolsBundle\Utils;

class ArrayUtils
{
    /**
     * Call array_filter recursively.
     *
     * @see http://php.net/manual/fr/function.array-filter.php
     *
     * @param array    $array
     * @param callable $callback
     * @param int      $flag
     *
     * @return array
     */
    public function filterRecursive(array $array, callable $callback, int $flag = 0): array
    {
        $result = [];

        $recursivable = array_filter($array, 'is_array');

        foreach ($recursivable as $key => $item) {
            if (is_array($item) && !empty($item)) {
                $filtered = $this->filterRecursive($item, $callback, $flag);
                if (!empty($filtered)) {
                    $result[$key] = $filtered;
                }
            }
        }

        $notRecursivable = array_diff_key($array, $recursivable);

        $result += array_filter($notRecursivable, $callback, $flag);

        return $result;
    }

    /**
     * <code>
     * $options = [
     *    'ignore_keys'       => [],           // List of keys to ignore when building the path.
     *    'prefix'            => '',           // Initial prefix of each path
     *    'flatten_numeric_keys' => false,     // Rather or not to flatten arrays that contains only numeric keys
     *    'keep_empty_arrays' => false,        // If keeping final arrays; Rather or not keeping the empty ones
     * ];
     * </code>
     *
     * @param array $array
     * @param array $options
     *
     * @return array
     */
    public function flatten(array $array, array $options): array
    {
        $options = array_replace([
            'ignore_keys'                 => [],
            'prefix'                      => '',
            'keep_empty_arrays'           => false,
            'flatten_numeric_keys'        => false,
        ], $options);

        $result                  = [];
        $prefix                  = $options['prefix'];
        $ignore                  = $options['ignore_keys'];
        $keepEmptyArrays         = $options['keep_empty_arrays'];
        $flattenNumericKeys      = $options['flatten_numeric_keys'];
        $isNumericOnly           = !$flattenNumericKeys;

        if (false === $flattenNumericKeys) {
            $keys = array_keys($array);
            array_walk($keys, function ($key) use (&$isNumericOnly) {
                $isNumericOnly = $isNumericOnly && is_numeric($key);
            });
        }

        foreach ($array as $key => $item) {
            $fullKey = $prefix;
            if (!in_array($key, $ignore, true)) {
                $fullKey = sprintf('%s[%s]', $prefix, $key);
            }

            $options['prefix'] = $fullKey;

            if (false === $flattenNumericKeys && true === $isNumericOnly) {
                $numericArray = $array;
                if (false === $keepEmptyArrays) {
                    $numericArray = $this->filterRecursive($array, function ($value) {
                        return '' !== trim($value);
                    });
                }

                if (true === $keepEmptyArrays || (false === $keepEmptyArrays && !empty($numericArray))) {
                    $result[$prefix] = $numericArray;
                }
            } else {
                if (is_array($item) && !empty($item)) {
                    $result += $this->flatten($item, $options);
                } else {
                    if (!empty($item) || $keepEmptyArrays) {
                        $result[$fullKey] = $item;
                    }
                }
            }
        }

        return $result;
    }
}
