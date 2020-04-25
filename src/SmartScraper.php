<?php
namespace Megroplan\Crawler;

use GuzzleHttp\Client;

class SmartScraper {
    private $client;
    private $baseUrl = 'http://localhost:8180/';

    public function getClient() {    
        
        if ($this->client) {
            return $this->client;
        }
        $this->client = new Client(['base_uri' => $this->baseUrl]);

        return $this->client;
    }

    /**
     * return mixed array see smart scraper response in git repo
     */
    public function parseIngredients($ingredientLines) {

        $wrappedIngredients = $this->wrapIngredients($ingredientLines);

        $response = $this->getClient()->request('POST', 'parse-ingredients', [
            'body' => $wrappedIngredients
        ]);

        return json_decode($response->getBody(), true);
    }

    private function wrapIngredients($ingredientLines)
    {
        $wrappedIngredients = '';
        foreach ($ingredientLines as $line) {
            $wrappedIngredients .= '<li>' . $line . '</li>';
        }

        return $wrappedIngredients;
    }

    /**
     * return string[] list of ingredient names
     */
    public function toIngredientNameList($smartIngredients)
    {
        $ingredients = [];
        foreach ($smartIngredients['ingredients'] as $ing) {
            $ingredients[] = $ing['name'];
        }

        return $ingredients;
    }
}