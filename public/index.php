<?php
require "../bootstrap.php";

use Src\Controller\TransportController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Endpoints should start with /delivery
// otherwise, you will be redirected to the swagger page
if ($uri[1] !== 'delivery') {
    header("Location: /web/index.html", true, 301);
    exit();
}

$type = null;
if (isset($uri[2])) {
    $type = $uri[2];
}

$controller = new TransportController($_SERVER["REQUEST_METHOD"], $type);
$controller->processRequest();
?>
