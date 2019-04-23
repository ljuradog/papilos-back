<?php
require_once 'comun/class.dbDriver.php';

header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');


$db = new DBDriver(PDOConfig::getInstance());
$response = new stdClass;

$requestType = $_SERVER['REQUEST_METHOD'];
$request = file_get_contents('php://input');
//die($requestType);
if ($requestType == "GET" || !is_null(json_decode($request)) ) {
    switch ($requestType) {
        case 'GET':
            $response->usuarios = getUsers($db);
            break;

    }
}

//RESPUESTA
echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

// Obtiene los usuarios
function getUsers($db) {
    return $db->query("select * from usuarios");
}