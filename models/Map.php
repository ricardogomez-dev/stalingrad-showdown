<?php

require_once 'Database.php';

class Map {
    private $collection;

    const EMPTY_CELL = 0;
    const TANK_CELL = 1;
    const OBSTACLE_CELL = 2;

    public function __construct() {
        $database = new Database();
        $client = $database->getClient();
        $this->collection = $client->stalingard_showdown->maps;
    }

    public function getCollection() {
        return $this->collection;
    }
}