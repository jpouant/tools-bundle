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

    /**
     * Copy directory recursively
     *
     * @param string $source
     * @param string $dest
     *
     * @return bool
     */
    public static function copyr($source, $dest)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            static::copyr("$source/$entry", "$dest/$entry");
        }

        // Clean up
        $dir->close();

        return true;
    }
}
