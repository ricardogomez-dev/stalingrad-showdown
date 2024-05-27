<?php

require_once 'Database.php';

class Leaderboard {
    private $collection;

    public function __construct() {
        $database = new Database();
        $client = $database->getClient();
        $this->collection = $client->stalingard_showdown->leaderboard;
    }

    public function getCollection() {
        return $this->collection;
    }
}