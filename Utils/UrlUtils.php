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
        '.co.uk',
    ];

    /**
     * @var StringUtils
     */
    private $stringUtils;

    /**
     * UrlUtils constructor.
     *
     * @param StringUtils $stringUtils
     */
    public function __construct(StringUtils $stringUtils = null)
    {
        $this->stringUtils = $stringUtils ?? new StringUtils();
    }

    /**
     * Extract the host from the URL and remove 'www.' and the trailing '/'
     *
     * @param string $url
     *
     * @return string
     */
    public function urlToString(string $url): string
    {
        $result = parse_url($url, PHP_URL_PATH);
        $result = rtrim($result, '/');
        if ('' === trim($result)) {
            $result = parse_url($url);
            if (array_key_exists('host', $result)) {
                $result = $result['host'];
                $result = rtrim($result, '/');
            } else {
                $result = '';
            }
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
    public function extractMainDomainFromUrl(string $url): string
    {
        $matchesMainDomain = null;
        $hostString        = self::urlToString($url);
        $pattern           = '/[^.]+\\.[^.]+$/';

        foreach (self::$excludedTld as $extension) {
            if ($this->stringUtils->endsWith($hostString, $extension)) {
                $pattern = sprintf('/.*\\.(.*%s)/', str_replace('.', '\.', $extension));
                break;
            }
        }

        preg_match($pattern, $hostString, $matchesMainDomain);

        return array_pop($matchesMainDomain);
    }

    /**
     * Extract all sub domains from a url
     *
     * @param string $url
     *
     * @return string
     */
    public function extractSubDomainsFromUrl(string $url): string
    {
        $mainDomain = $this->extractMainDomainFromUrl($url);

        return preg_replace('/\.' . $mainDomain . '/', '', $url);
    }

    /**
     * Extract the first sub domain from a url
     *
     * @param string $url
     *
     * @return string
     */
    public function extractFirstSubDomainFromUrl(string $url): string
    {
        $subDomains = $this->extractSubDomainsFromUrl($url);

        $matchesSubDomain = null;
        preg_match('/[^.]+$/', $subDomains, $matchesSubDomain);

        return array_pop($matchesSubDomain);
    }

    /**
     * Figures out the filename of the URL
     *
     * @param string $url
     *
     * @return string|null
     */
    public function getUrlFilename(string $url): ?string
    {
        // Take everything after the last slash:
        // https://mywebsite.com/file/doc/CGV_3A.pdf => CGV_3A.pdf
        if (preg_match('/\/([^\/]+)$/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
