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
$peticion = json_decode($request);
error_log(print_r($peticion, true));

if ($requestType == "GET" || !is_null($peticion) ) {
    switch ($requestType) {
        case 'GET':
            $response->productos = getCatalogo($db, $peticion);
            break;
        case 'POST':
            $response->productos = postCatalogo($db, $peticion);
            break;
        
    }
}

//RESPUESTA
echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

// Obtiene los usuarios
function getCatalogo($db, $request) {
    return $db->query("select * from productos
    left join catalogo on productos.productocod = catalogo.productocod
    where proveedorcod is null or proveedorcod = ". $request->idUsuario);
}

// Actualiza los precios
function postCatalogo($db, $request) {
    return $db->query("select * from productos
    left join catalogo on productos.productocod = catalogo.productocod
    where proveedorcod is null or proveedorcod = ". $request->idUsuario);
}