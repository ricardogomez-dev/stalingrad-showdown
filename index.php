<?php
require_once 'CORS.php';
require_once 'vendor/autoload.php';
require_once 'controllers/MapController.php';
require_once 'controllers/LeaderboardController.php';
require_once 'controllers/BattleController.php';

// Router 
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    '/get-random-map' => ['MapController', 'getRandomMap'],
    '/create-random-map' => ['MapController', 'createRandomMap'],
    '/get-leaderboard' => ['LeaderboardController', 'getLeaderboard'],
    '/add-player-to-leaderboard' => ['LeaderboardController', 'addPlayerToLeaderboard'],
    '/simulate' => ['BattleController', 'startBattle'],
];

if (array_key_exists($requestUri, $routes)) {
    $controllerName = $routes[$requestUri][0];
    $methodName = $routes[$requestUri][1];
    
    $controller = new $controllerName();
    
    if (method_exists($controller, $methodName)) {
        if ($method === 'POST') {
            $data = file_get_contents('php://input');
            $data = json_decode($data, true);
            $controller->$methodName($data);
        } else {
            $controller->$methodName();
        }
    } else {
        http_response_code(404);
        echo '404 Method Not Found';
    }
} else {
    http_response_code(404);
    echo '404 Not Found';
}