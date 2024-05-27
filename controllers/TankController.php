<?php

require_once 'models/Tank.php';

class TankController {
    private $collection;

    public function __construct() {
        $model = new Tank();
        $this->collection = $model->getCollection();
    }

    public function getTwoRandomTanks() {
        $cursor = $this->collection->aggregate([
            ['$sample' => ['size' => 2]]
        ]);
        
        $tanks = iterator_to_array($cursor);

        return $tanks;
    }
}