<?php

require_once 'Database.php';
require_once 'controllers/MapController.php';

function seedMaps() {

    $faker = Faker\Factory::create();
    $controller = new MapController;
    
    $database = new Database();
    $client = $database->getClient();
    $collection = $client->stalingard_showdown->maps;

    $maps = [];

    for ($i = 0; $i < 20; $i++) {

        $rows = rand(50, 75);
        $cols = rand(50, 75);
        $obstacles = (int)(($rows * $cols) * 0.3);

        $map = $controller->createRandomMap($rows, $cols, $obstacles);
        array_push($maps, $map);
    }

    $collection->insertMany($maps);
}

seedMaps();