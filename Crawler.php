<?php
require 'vendor/autoload.php';

use Megroplan\Crawler\RecipeScraper;
use Megroplan\Crawler\SmartScraper;
use Megroplan\Crawler\SitemapParser;
use Megroplan\Crawler\MeiliSearch;
use Megroplan\Crawler\Store;
use Megroplan\Crawler\Utils;
use Megroplan\Crawler\StoreUrl;
use Megroplan\Crawler\Exceptions\NotSupportedException;

runCrawler(500);

function runCrawler($limit = 500) {
    $scraper = new RecipeScraper();
    $smartScraper = new SmartScraper();

    $db = __DIR__ . '/storage/recipesDB';
    $store = new Store($db, 'recipes');
    $urlStore = new StoreUrl($db, 'urls');

    $index = 1;
    $urls = $urlStore->getUrls($limit);
    foreach ($urls as $url) {
        try {
            echo $index . " - _id: " . $url['_id'] . "  Crawling: " . $url['url'] . "\n";

            $recipe = $scraper->getRecipe($url['url']);

            $smartIngredients = $smartScraper->parseIngredients($recipe['ingredients']);
            $recipe['ingredientList'] = $smartIngredients;
            $recipe['ingredients'] = $smartScraper->toIngredientNameList($smartIngredients);
            $recipe['prepTime'] = Utils::ISO8601FormatToMinutes($recipe['prepTime']);
            $recipe['totalTime'] = Utils::ISO8601FormatToMinutes($recipe['totalTime']);
            $recipe['cookTime'] = Utils::ISO8601FormatToMinutes($recipe['cookTime']);

            foreach($recipe['nutrition'] as $key => $value) {
                $recipe['nutrition'][$key] = Utils::getFloatFromString($value)[0];
            }

            $store->saveRecipe($recipe);

            $rand = rand(5,20);
            if ($index % $rand == 0) {
                echo "\n sleep " . $rand . "  \n";
                sleep(rand(1,3));
            }
            if ($index % 25 == 0) {
                echo "\n sleep 2 \n";
                sleep(5);
            }
        } catch(NotSupportedException $e) {
            echo "\n" .$e->getMessage() . "\n";
        }

        $urlStore->saveUrl($url);
        $index++;
    }
}