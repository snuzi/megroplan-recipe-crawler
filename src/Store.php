<?php

namespace Megroplan\Crawler;

use SleekDB\SleekDB;

class Store
{
    private $store;

    public function __construct($database)
    {
        $this->database = $database;
        $this->init();
    }

    protected function init(): void
    {
        $this->store = SleekDB::store($this->storeName(), $this->databaseDir());
    }

    protected function storeName(): string
    {
        return 'recipes';
    }

    protected function databaseDir(): string
    {
        return $this->database;
    }

    public function getStore()
    {
        return $this->store;
    }

    public static function store(string $database)
    {
        $store = new Store($database);

        return $store->getStore();
    }

    public function insert($data)
    {
        return $this->store->insert($data);
    }

    public function saveRecipe($recipe) 
    {
        $query = $this->store->where('url', '=', $recipe['url']);
        $recipeInDB = $this->store->where('url', '=', $recipe['url'])->fetch();
    
        if (!$recipeInDB) {
            $this->store->insert($recipe);
        } else {
            $this->store->where('url', '=', $recipe['url'])->update($recipe);
        }
    }
}