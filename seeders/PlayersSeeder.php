<?php

require_once 'Database.php';

function seedUsers() {

    $faker = Faker\Factory::create();
    
    $database = new Database();
    $client = $database->getClient();
    $collection = $client->stalingard_showdown->players;

    $players = [];

    for ($i = 0; $i < 20; $i++) {
        $player = [
            'name' => $faker->name(), 
            'email' => $faker->email()
        ];
        array_push($players, $player);
    }

    $collection->insertMany($players);
}

seedUsers();