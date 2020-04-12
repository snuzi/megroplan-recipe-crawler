<?php
namespace Megroplan\Crawler;

use vipnytt\SitemapParser as BaseSitemapParser;
use vipnytt\SitemapParser\Exceptions\SitemapParserException;

class SitemapParser {
    static function getUrls($sitemapUrl)
    {
        try {
            $parser = new BaseSitemapParser('MyCustomUserAgent');
            $parser->parse($sitemapUrl);

            // Loop this as $url => $tags
            // Ex: $tags['lastmod']
            return $parser->getURLs();
        } catch (SitemapParserException $e) {
            echo $e->getMessage();
        }
    }
}