<?php
require '../vendor/autoload.php';

use Src\Config\Database;
use Src\Config\Cors;
use Src\Request\Request;

$setCors = new Cors();
$setCors->cors();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeload();

$database = new Database();
$dbConn = $database->getConnection();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

if ($uri[1] !== 'api' || count($uri) !== 2) {
  header("HTTP/1.1 404 Not Found");
  exit();
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method and post ID to the Post and process the HTTP request:
$controller = new Request($dbConn, $requestMethod);
$controller->processRequest();
