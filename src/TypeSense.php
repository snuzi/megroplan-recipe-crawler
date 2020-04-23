<?php
namespace Megroplan\Crawler;

use Devloops\Typesence\Client;

class TypeSense {
    private $client;

    private $host = 'localhost';
    private $masterKey = 'test';
    private $indexName = 'recipes.dev';
    private $port = '8108';

    private $indexConfig =  [
        'fields' => [
            [
                'name' => 'id',
                'type' => 'string',
            ],
            [
                'name' => '_id',
                'type' => 'int32',
            ],
            [
                'name' => 'ingredients',
                'type' => 'string[]',
            ],
            [
                'name' => 'categories',
                'type' => 'string[]',
                'facet' => true,
            ],
            [
                'name' => 'cuisines',
                'type' => 'string[]',
                'facet' => true,
            ],
            [
                'name' => 'instructions',
                'type' => 'string[]',
            ],
            [
                'name' => 'description',
                'type' => 'string',
            ],
            [
                'name' => 'notes',
                'type' => 'string',
            ],
            [
                'name' => 'name',
                'type' => 'string',
            ],
            [
                'name' => 'image',
                'type' => 'string',
            ],
            [
                'name' => 'cookingMethod',
                'type' => 'string',
            ],
            [
                'name' => 'prepTime',
                'type' => 'string',
            ],
            [
                'name' => 'totalTime',
                'type' => 'string',
            ],
            [
                'name' => 'cookTime',
                'type' => 'string',
            ],
            [
                'name' => 'publisher',
                'type' => 'string',
            ],
            [
                'name' => 'author',
                'type' => 'string',
            ],
            [
                'name' => 'url',
                'type' => 'string',
            ],
            [
                'name' => 'yield',
                'type' => 'string',
            ]
        ],
        'default_sorting_field' => '_id',
    ];

    public function __construct($indexName) {
        $this->indexName = $indexName;
    }

    public function getClient()
    {
        if ($this->client) {
            return $this->client;
        }

        $this->client =  $client = new Client(
            [
              'master_node' => [
                'host' => $this->host,
                'port' => $this->port,
                'protocol' => 'http',
                'api_key' => $this->masterKey,
              ],
              'timeout_seconds' => 5,
            ]
          );

        return $this->client;
    }


    public function getIndex()
    {
        return $this->getClient()->collections[$this->indexName]->retrieve();
    }

    public function createIndex() {
        $this->indexConfig['name'] = $this->indexName;
        $indexer = $this->getClient()->collections->create($this->indexConfig);

        return $this;
    }

    public function add(array $document)
    {
        if($this->getDocument($document['id'])) {
            $this->delete($document['id']);
        }

        $this->getClient()->collections[$this->indexName]
            ->documents->create($document);
    }

    public function delete($documentsId)
    {
        $this->getClient()->collections[$this->indexName]
            ->documents[$documentsId]->delete();
    }

    public function getDocument($documentsId)
    {
        try {
            return $this->getClient()->collections[$this->indexName]
                ->documents[$documentsId]->retrieve();
        } catch (\Devloops\Typesence\Exceptions\ObjectNotFound $e) {
            return null;
        }
    }

    public function deleteIndex()
    {
        return $this->getClient()->collections[$this->indexName]->delete();
    }

    public function search(array $params = [])
    {
        return $this->getClient()
            ->collections[$this->indexName]
            ->documents->search($params);
    }
}