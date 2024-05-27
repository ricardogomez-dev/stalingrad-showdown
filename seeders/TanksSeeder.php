<?php

require_once 'Database.php';

function seedTanks() {

    $faker = Faker\Factory::create();
    
    $database = new Database();
    $client = $database->getClient();
    $collection = $client->stalingard_showdown->tanks;

    $tanks = [];

    for ($i = 0; $i < 20; $i++) {
        $tank = [
            'speed' => rand(1, 5), 
            'turretRange' => rand(1, 5)
        ];
        array_push($tanks, $tank);
    }

    $collection->insertMany($tanks);
}

seedTanks();