<?php
require 'vendor/autoload.php';

use Megroplan\Crawler\RecipeScraper;
use Megroplan\Crawler\SmartScraper;
use Megroplan\Crawler\SitemapParser;
use Megroplan\Crawler\MeiliSearch;
use Megroplan\Crawler\Store;
use Megroplan\Crawler\StoreUrl;
use Megroplan\Crawler\Exceptions\NotSupportedException;
use Megroplan\Crawler\TypeSense;
use Devloops\Typesence\Exceptions\RequestMalformed;

search();
exit;

$typeSense = new TypeSense('recipes.dev');
//$typeSense->deleteIndex();
//$typeSense->createIndex();

//var_dump($typeSense->getIndex());

index(500);

function removeNulls($item) {
    return $item === null ? '' : $item;
}

function index($limit = 500) {

        $typeSense = new TypeSense('recipes.dev');

        $db = __DIR__ . '/storage/recipesDB';
        $store = new Store($db, 'recipes');

        $index = 1;
        $recipes = $store->getStore()->limit($limit)->fetch();
        foreach ($recipes as $recipe) {
            try {
                echo $index . " - _id: " . $recipe['_id'] . "  Indexing: " . $recipe['url'] . "\n";
                
                $recipe['cuisines'] = $recipe['cuisines'] == '' ? [] : $recipe['cuisines'];
                $recipe['ingredients'] = !$recipe['ingredients'] ? [] : $recipe['ingredients'];
                $recipe = array_map('removeNulls', $recipe);

                $recipe['id'] = '' . $recipe['_id'];
                $typeSense->add($recipe);

                if ($index % 50 == 0) {
                    sleep(1);
                }
            } catch (RequestMalformed $e) {}

        $index++;
    }
}

function search() {
    $params = [
        'q' => 'tomato',
        'query_by'  => 'name,description,notes,ingredients,instructions',
        'facet_by' => 'cuisines,categories',
        'include_fields' => 'name,id,_id,url'
    ];

    $typeSense = new TypeSense('recipes.dev');
    $result = $typeSense->search($params);

    var_dump($result);
}