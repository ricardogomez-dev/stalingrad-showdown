<?php
require_once 'controllers/TankController.php';
require_once 'controllers/MapController.php';
require_once 'controllers/PlayerController.php';

class BattleController {

    public function startBattle() {

        $tankController = new TankController;
        $playerController = new PlayerController;
        $mapController = new MapController;

        $tanks = $tankController->getTwoRandomTanks();
        $players = $playerController->getTwoRandomPlayers();

        return $mapController->getRandomMap($tanks, $players);
    }
}