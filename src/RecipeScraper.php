<?php
namespace Megroplan\Crawler;

use Goutte\Client;

class RecipeScraper {
    private $client;
    private $scraper;

    public function getClient() {    
        
        if ($this->client) {
            return $this->client;
        }
        $this->client = new Client();

        return $this->client;
    }

    public function getScraper() {    
        
        if ($this->scraper) {
            return $this->scraper;
        }
        $this->scraper = \RecipeScraper\Factory::make();

        return $this->scraper;
    }

    public function getRecipe(string $url) {
        $crawler = $this->getClient()->request('GET', $url);

        if (!$this->getScraper()->supports($crawler)) {
            throw new \Exception('"{$url} not currently supported!"');
        }
        
        return $this->getScraper()->scrape($crawler);
    }
}