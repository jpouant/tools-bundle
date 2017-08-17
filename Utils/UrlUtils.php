<?php

namespace Neirda24\Bundle\ToolsBundle\Utils;

use InvalidArgumentException;

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
     * @param string $url
     *
     * @throws InvalidArgumentException
     */
    private function validateUrl(string $url): void
    {
        $result = parse_url($url);

        if (false === $result) {
            throw new InvalidArgumentException(sprintf('The given string is not a valid URL. `%s`', $url));
        }
    }

    /**
     * Extract the host from the URL and remove the trailing '/'
     *
     * @param string $url
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function extractHost(string $url): string
    {
        $this->validateUrl($url);
        $result = parse_url($url, PHP_URL_HOST);
        $result = rtrim($result, '/');

        return $result;
    }

    /**
     * Extract the host from the URL and remove 'www.' and the trailing '/'
     *
     * @param string $url
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function urlToString(string $url): string
    {
        $result = $this->extractHost($url);

        $result = preg_replace('`^www\.`i', '', $result);

        return $result;
    }

    /**
     * Extract the domain from a url
     *
     * @param string $url
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function extractMainDomainFromUrl(string $url): string
    {
        $matchesMainDomain = null;
        $hostString        = $this->extractHost($url);
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
     *
     * @throws InvalidArgumentException
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
     *
     * @throws InvalidArgumentException
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

    /**
     * @param string $url
     * @param bool   $query
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function extractPath(string $url, bool $query = true): string
    {
        $this->validateUrl($url);
        $result = parse_url($url, PHP_URL_PATH);

        if (true === $query) {
            $queryResult = parse_url($url, PHP_URL_QUERY);
            if ('' !== trim($queryResult)) {
                $result .= sprintf('?%s', $queryResult);
            }
        }

        $fragmentResult = parse_url($url, PHP_URL_FRAGMENT);
        if ('' !== $fragmentResult) {
            $result .= sprintf('#%s', $fragmentResult);
        }

        return $result;
    }
}
