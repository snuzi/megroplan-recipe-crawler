<?php
require 'vendor/autoload.php';

use Megroplan\Crawler\RecipeScraper;
use Megroplan\Crawler\SmartScraper;
use Megroplan\Crawler\SitemapParser;
use Megroplan\Crawler\MeiliSearch;
use Megroplan\Crawler\Store;
use Megroplan\Crawler\StoreUrl;
use Megroplan\Crawler\Exceptions\NotSupportedException;


search();

$meiliSearch = new MeiliSearch('recipesdev');
$meiliSearch->deleteIndex();
$meiliSearch->createIndex();

//var_dump($typeSense->getIndex());

index(3000);

function removeNulls($item) {
    return $item === null ? '' : $item;
}

function index($limit = 500) {

        $meiliSearch = new MeiliSearch('recipesdev');

        $db = __DIR__ . '/storage/recipesDB';
        $store = new Store($db, 'recipes');

        $index = 1;
        $recipes = $store->getStore()->limit($limit)->fetch();
        foreach ($recipes as $recipe) {
            try {
                echo $index . " - _id: " . $recipe['_id'] . "  Indexing: " . $recipe['url'] . "\n";
                $recipe = array_map('removeNulls', $recipe);

                $recipe['cuisines'] = $recipe['cuisines'] == '' ? [] : array_values($recipe['cuisines']);
                $recipe['categories'] = $recipe['categories'] == '' ? [] : $recipe['categories'];
                $recipe['ingredients'] = !$recipe['ingredients'] ? [] : $recipe['ingredients'];

                $recipe['cookTime'] = $recipe['cookTime'] ? $recipe['cookTime'] : 0;
                $recipe['prepTime'] = $recipe['prepTime'] ? $recipe['prepTime'] : 0;
                $recipe['totalTime'] = $recipe['totalTime'] ? $recipe['totalTime'] : 0;
                $recipe['notes'] = $recipe['notes'] ? implode(' ', $recipe['notes']) : '';
                $recipe['id'] = '' . $recipe['_id'];

                $meiliSearch->add([$recipe]);

                if ($index % 50 == 0) {
                    sleep(1);
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
               // var_dump($recipe['categories'] );
                
            }

        $index++;
    }
}

function search() {
    $params = [
        'q' => 'olive'
    ];

    $meiliSearch = new MeiliSearch('recipesdev');
    //var_dump($typeSense->getIndex()); exit;
    $result = $meiliSearch->search('olive', $params);

    foreach($result['hits'] as $doc) {
        $result['ids'][] = intval($doc['id']);
    }

    //$result['total_pages'] = ceil($result['found'] / $params['per_page']);
    unset($result['hits']);
    
    var_dump($result);

    exit;
}