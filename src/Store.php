<?php

namespace Megroplan\Crawler;

use SleekDB\SleekDB;

class Store
{
    private $store;
    private $storeName;

    public function __construct($databaseDir, $storeName)
    {
        $this->databaseDir = $databaseDir;
        $this->storeName = $storeName;
        $this->init();
    }

    protected function init(): void
    {
        $this->store = SleekDB::store($this->storeName(), $this->databaseDir());
    }

    protected function storeName(): string
    {
        return $this->storeName;
    }

    protected function databaseDir(): string
    {
        return $this->databaseDir;
    }

    public function getStore()
    {
        return $this->store;
    }

    public static function store(string $databaseDir)
    {
        $store = new Store($databaseDir);

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