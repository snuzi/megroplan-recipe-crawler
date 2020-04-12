<?php
require 'vendor/autoload.php';

use Megroplan\Crawler\RecipeScraper;
use Megroplan\Crawler\SitemapParser;
use Megroplan\Crawler\TntSearch;

$scraper = new RecipeScraper();

/**
$sitemapUrls = SitemapParser::getUrls('https://www.jamieoliver.com/recipes.xml');
foreach ($sitemapUrls as $url => $tags) {
    echo $url . "\n";
}
 */

$url = 'http://allrecipes.com/recipe/139917/joses-shrimp-ceviche/';

$recipe = $scraper->getRecipe($url);

$tntSearch = new TntSearch();
//$tntSearch->createIndex();

$recipe['id'] = $recipe['url'];

$recipe = ['id' => 'https://test.com', 'notes' => 'bla blabla'];
$tntSearch->insert($recipe);


$res = $tntSearch->search('bla ');
var_dump($res);
//var_dump($recipe);
