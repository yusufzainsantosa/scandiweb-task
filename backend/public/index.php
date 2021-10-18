<?php
require '../vendor/autoload.php';

use Src\Config\Database;
use Src\Request\Request;

header("Access-Control-Allow-Origin: http://localhost:8081");   
header("Content-Type: application/json; charset=UTF-8");    
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");    
header("Access-Control-Max-Age: 3600");    
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");  

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
