<?php
namespace Megroplan\Crawler;

use MeiliSearch\Client;

class MeiliSearch {
    private const INDEX_NAME = 'megroplan.index';
    private const INDEX_SETTINGS = [];

    private $client;
    
    private static $instance = null;

    private $host = 'http://localhost:7700';
    private $masterKey = 'masterKey';
    private $indexName = 'indexName';

    public function __construct($index) {
        $this->indexName = $index;
    }

    public function getClient()
    {
        if ($this->client) {
            return $this->client;
        }

        $this->client = new Client($this->host);

        return $this->client;
    }


    public function getIndex()
    {
        return $this->getClient()->getIndex($this->indexName);
    }

    public function createIndex($primaryKey = 'id') {
        $index = $this->getClient()->createIndex([
                'uid' => $this->indexName,
                'primaryKey' => $primaryKey
            ]);

        $this->updateIndexSettings();

        return $this;
    }

    public function deleteIndex() {
        $this->getClient()->deleteIndex($this->indexName);
    }

    public function updateIndexSettings() {
        $this->getIndex()->updateSettings(SELF::INDEX_SETTINGS);
    }

    public function add(array $documents, $returnStatus = false)
    {
        $updateItem = $this->getIndex()->addDocuments($documents); // => { "updateId": 0 }
        if ($returnStatus) {
            return $this->getIndex()->getUpdateStatus($updateItem['updateId']);
        }
    }

    public function delete($documentsIds)
    {
        $this->getIndex()->deleteDocument($documentsIds);
    }

    public function search(string $term, array $params = [])
    {
        return $this->getIndex()->search($term);
    }
}