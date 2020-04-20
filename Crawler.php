<?php
require 'vendor/autoload.php';

use Megroplan\Crawler\RecipeScraper;
use Megroplan\Crawler\SitemapParser;
use Megroplan\Crawler\MeiliSearch;
use Megroplan\Crawler\Store;

$scraper = new RecipeScraper();

$db = __DIR__ . '/storage/recipesDB';
$store = new Store($db);

$meiliSearch = new MeiliSearch('recipes');
//$meiliSearch->createIndex('recipes');

$sitemapUrls = SitemapParser::getUrls('https://www.jamieoliver.com/recipes.xml');

$index = 1621;
$total = count($sitemapUrls) - 1;
$sitemapUrls = array_slice($sitemapUrls, $index, $total);

//exit;

foreach ($sitemapUrls as $url => $tags) {
    echo $index . " - Crawling: " . $url . "\n";
    
    $recipe = $scraper->getRecipe($url);
    $recipe['id'] = $index;
  
    $store->saveRecipe($recipe);

    //$meiliSearch->add([$recipe]);

    if ($index % rand(5,20) == 0) {
        sleep(rand(2,20));
    }
    $index++;
}

$url = 'https://www.jamieoliver.com/recipes/sauce-recipes/bolognese-sauce';

//$recipe = $scraper->getRecipe($url);

//$tntSearch->createIndex();

//$recipe['id'] = $recipe['url'];

//$recipe = ['id' => 'https://test.com', 'notes' => 'bla blabla'];
//$tntSearch->insert($recipe);
//$res = $tntSearch->search('bla ');
//var_dump($res);
//var_dump($recipe['image']);
