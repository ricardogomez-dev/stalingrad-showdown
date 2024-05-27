<?php

require_once 'models/Leaderboard.php';

class LeaderboardController {
    private $leaderboard;

    public function __construct() {
        $model = new Leaderboard();
        $this->leaderboard = $model->getCollection();
    }

    public function getLeaderboard() {
        $cursor = $this->leaderboard->aggregate([
            [
                '$group' => [
                    '_id' => '$player_id',
                    'name' => ['$first' => '$name'],
                    'highest_score' => ['$max' => '$score']
                ]
            ],
            [
                '$sort' => [
                    'highest_score' => -1
                ]
            ]
        ]);

        $leaderboard = iterator_to_array($cursor);

        echo json_encode($leaderboard);
    }

    public function addPlayerToLeaderboard($data) {
        return $this->leaderboard->insertOne([
            'name' => $data['name'],
            'player_id' => $data['player_id'],
            'score' => rand(750, 2000)
        ]);
    }
}