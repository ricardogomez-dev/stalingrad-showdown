<?php

require_once 'Database.php';

class Tank {
    private $collection;

    public function __construct() {
        $database = new Database();
        $client = $database->getClient();
        $this->collection = $client->stalingard_showdown->tanks;
    }

    public function getCollection() {
        return $this->collection;
    }
}