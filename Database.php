<?php

class Database {
    private $client;

    public function __construct() {
        $host = 'mongodb';
        $port = 27017;
        $username = 'root';
        $password = 'password';
        $database = 'stalingard_showdown';

        $uri = "mongodb://$username:$password@$host:$port/$database" . "?authSource=admin";

        $this->client = new MongoDB\Client($uri);
    }

    public function getClient() {
        return $this->client;
    }
}
