<?php

require_once 'models/Player.php';

class PlayerController {
    private $collection;

    public function __construct() {
        $model = new Player();
        $this->collection = $model->getCollection();
    }

    public function getTwoRandomPlayers() {
        $cursor = $this->collection->aggregate([
            ['$sample' => ['size' => 2]]
        ]);
        
        $players = iterator_to_array($cursor);

        return $players;
    }
}