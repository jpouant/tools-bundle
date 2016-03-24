<?php

namespace Neirda24\Bundle\ToolsBundle\Utils;

class UrlUtils
{
    /**
     * tld = Top Level Domains
     *
     * @var array
     */
    protected static $excludedTld = [
        '.co.uk'
    ];

    /**
     * Extract the host from the URL and remove 'www.' and the trailing '/'
     *
     * @param string $url
     *
     * @return string
     */
    public static function urlToString($url)
    {
        $result = parse_url($url, PHP_URL_PATH);

        if (true === StringUtils::endsWith($result, '/')) {
            $result = rtrim($result, '/');
        }

        $result = preg_replace('`^www\.`i', '', $result);

        return $result;
    }

    /**
     * Extract the domain from a url
     *
     * @param string $url
     *
     * @return string
     */
    public static function extractMainDomainFromUrl($url)
    {
        $matchesMainDomain = null;
        $hostString        = self::urlToString($url);
        $pattern           = '/[^.]+\\.[^.]+$/';

        foreach (self::$excludedTld as $extension) {
            if (StringUtils::endsWith($hostString, $extension)) {
                $pattern = sprintf('/.*\\.(.*%s)/', str_replace('.', '\.', $extension));
                break;
            }
        }

        preg_match($pattern, $hostString, $matchesMainDomain);
        $mainDomain = array_pop($matchesMainDomain);

        return $mainDomain;
    }

    /**
     * Extract all sub domains from a url
     *
     * @param string $url
     *
     * @return string
     */
    public static function extractSubDomainsFromUrl($url)
    {
        $mainDomain = self::extractMainDomainFromUrl($url);

        $subDomains = preg_replace('/\.' . $mainDomain . '/', '', $url);

        return $subDomains;
    }

    /**
     * Extract the first sub domain from a url
     *
     * @param string $url
     *
     * @return string
     */
    public static function extractFirstSubDomainFromUrl($url)
    {
        $subDomains = self::extractSubDomainsFromUrl($url);

        $matchesSubDomain = null;
        preg_match('/[^.]+$/', $subDomains, $matchesSubDomain);
        $firstSubDomain = array_pop($matchesSubDomain);

        return $firstSubDomain;
    }

    /**
     * Figures out the filename of the URL
     *
     * @param string $url
     *
     * @return string|null
     */
    public static function getUrlFilename($url)
    {
        // Take everything after the last slash:
        // https://mywebsite.com/file/doc/CGV_3A.pdf => CGV_3A.pdf
        if (preg_match('/\/([^\/]+)$/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
