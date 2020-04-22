<?php
namespace Megroplan\Crawler;

use SleekDB\SleekDB;

class StoreUrl {
    private $store;
    private $directory;
    private $storeName;

    public function __construct($directory, $storeName)
    {
        $this->directory = $directory;
        $this->storeName = $storeName;
        $this->init();
    }

    protected function init(): void
    {
        $this->store = SleekDB::store($this->storeName, $this->directory);
    }

    public function getStore()
    {
        return $this->store;
    }

    public function insert($data)
    {
        return $this->store->insert($data);
    }

    public function saveUrl($urlObject) 
    {
        $urlObject['host'] = parse_url($urlObject['url'], PHP_URL_HOST);
        $urlObject['updated_at'] = time();

        $found = $this->store
            ->where('url', '=', $urlObject['url'])
            ->fetch();
    
        if (!$found) {
            $this->store->insert($urlObject);
        } else {
            $this->store
                ->where('url', '=', $urlObject['url'])
                ->update($urlObject);
        }
    }

    

    public function getUrls($limit = 50, $host = null) 
    {
        if ($host) {
            return $this->store
            ->where('host', '=', $host)
            ->limit($limit)
            ->orderBy( 'desc', 'updated_at')
            ->fetch();
        }

        return $this->store
                ->limit($limit)
                ->orderBy( 'asc', 'updated_at')
                ->fetch();
    }
}