<?php

require_once 'api/LinksApi.php';
require_once 'api/TransitionsApi.php';
require_once 'models/Link.php';
require_once 'models/Transition.php';

use api\LinksApi;
use api\TransitionsApi;
use config\Database;
use models\Link;
use models\Transition;

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = trim($requestUri, '/');
$requestUri = explode('/', $requestUri);
if (array_key_exists(0, $requestUri) && $requestUri[0] == 'api') {
    try {
        if (!array_key_exists(1, $requestUri)) {
            throw new Exception('Not Found', 404);
        }
        $apiClassName = 'api\\' . ucfirst($requestUri[1]) . 'Api';
        if (!file_exists($apiClassName . '.php')) {
            throw new Exception('Not Found', 404);
        }
        $api = new $apiClassName();
        echo $api->run();
    } catch (Exception $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
} elseif (empty($requestUri[0])) {
    require_once 'views/form.php';
} else {
    $database = new Database();
    $db = $database->getConnection();
    $link = Link::findByHash($db, $requestUri[0]);
    if (!$link) {
        http_response_code(404);
        echo 'Page Not Found';
    }
    $transition = new Transition($db, [
        'link_id' => $link->id,
    ]);
    $transition->insert();
    header("Location: " . $link->url);
    exit;
}