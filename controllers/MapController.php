<?php
require_once 'models/Map.php';

class MapController {
    private $collection;

    public function __construct() {
        $model = new Map();
        $this->collection = $model->getCollection();
    }

    public function getRandomMap($tanks, $players) {
        $cursor = $this->collection->aggregate([
            ['$sample' => ['size' => 1]]
        ]);
            
        $map = iterator_to_array($cursor);

        echo json_encode([
            'map' => $map[0]['map'],
            'tanks' => $tanks,
            'players' => $players,
        ]);
    }

    public function createRandomMap($rows, $cols, $obstacles) {
        $map = array_fill(0, $rows, array_fill(0, $cols, Map::EMPTY_CELL));

        $this->placeItem($rows, $cols, $map, Map::TANK_CELL, 2);
        $this->placeItem($rows, $cols, $map, Map::OBSTACLE_CELL, $obstacles);

        $this->collection->insertOne(['map' => $map]);

        echo json_encode(['map' => $map]);
    }

    private function placeItem($rows, $cols, &$map, $item, $count) {
        while ($count > 0) {
            $row = rand(0, $rows - 1);
            $col = rand(0, $cols - 1);

            if ($map[$row][$col] == Map::EMPTY_CELL) {
                $map[$row][$col] = $item;
                $count--;
            }
        }
    }
}
