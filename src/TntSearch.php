<?php
namespace Megroplan\Crawler;

use TeamTNT\TNTSearch\TNTSearch as TNT;

class TNTSearch {
    private const INDEX_NAME = 'megroplan.index';
    private $client;

    public function getClient()
    {
        if ($this->client) {
            return $this->client;
        }

        $this->client = new TNT;

        $this->client->loadConfig([
           // 'driver' => 'filesystem',
            'storage' => __DIR__ . '/../storage/',
            //'extension' => 'txt',
           // 'stemmer' => \TeamTNT\TNTSearch\Stemmer\PorterStemmer::class//optional
        ]);

        return $this->client;
    }

    public function createIndex($indexName = self::INDEX_NAME) {
        $indexer = $this->getClient()->createIndex($indexName);
        //$indexer->query('SELECT id, article FROM articles;');
        //$indexer->setLanguage('german');
        $indexer->run();
    }

    public function insert(array $item)
    {
        $tnt = $this->getClient();
        $tnt->selectIndex(self::INDEX_NAME);
        $index = $tnt->getIndex();
        $index->insert($item);
    }

    public function update(string $id, array $item)
    {
        $tnt = $this->getClient();
        $tnt->selectIndex(self::INDEX_NAME);
        $index = $tnt->getIndex();
        $index->update($id, $item);
    }

    public function delete(string $id)
    {
        $tnt = $this->getClient();
        $tnt->selectIndex(self::INDEX_NAME);
        $index = $tnt->getIndex();
        $index->delete($id);
    }

    public function search(string $term)
    {
        $tnt = $this->getClient();
        $tnt->selectIndex(self::INDEX_NAME);
        $index = $tnt->getIndex();
        $tnt->fuzziness = true;

        //when the fuzziness flag is set to true, the keyword juleit will return
        //documents that match the word juliet, the default Levenshtein distance is 2
        return $tnt->search($term);
    }
}