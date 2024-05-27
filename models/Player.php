<?php

require_once 'Database.php';

class Player {
    private $collection;

    public function __construct() {
        $database = new Database();
        $client = $database->getClient();
        $this->collection = $client->stalingard_showdown->players;
    }

    public function getCollection() {
        return $this->collection;
    }
}