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
error_log("Peticion ". print_r($peticion, true));

if ($requestType == "GET" || !is_null($peticion) ) {
    switch ($requestType) {
        case 'GET':
            $idUsuario = filter_input(INPUT_GET, 'idUsuario', FILTER_SANITIZE_URL);
            if (is_null($idUsuario)) {
                $response->productos = getCatalogoCotizaciones($db, $idUsuario);
            } else {
                $response->productos = getCatalogo($db, $idUsuario);    
            }

            break;
        case 'POST':
            actualizarCatalogo($db, $peticion);
            $response->resultado = 'OK';
            break;
        
    }
}

//RESPUESTA
echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

// Obtiene los productos con precios para proveedores
function getCatalogo($db, $idUsuario) {
    return $db->query("select productos.*, catalogo.preciounitario from productos
    left join catalogo on productos.productocod = catalogo.productocod and (proveedorcod is null or proveedorcod = ". $idUsuario .")");
}

// Obtiene los productos
function getCatalogoCotizaciones($db, $idUsuario) {
    return $db->query("select * from productos");
}

// Actualiza los precios
function actualizarCatalogo($db, $request) {
    $idCatalogo = $db->set("select catalogocod from catalogo where proveedorcod = ? and productocod = ?;", [$request->idUsuario, $request->idProducto]);
    if (count($idCatalogo) > 0) {
        // Actualizo
        $db->set("update catalogo set preciounitario = ? where proveedorcod = ? and productocod = ?;", [$request->precio, $request->idUsuario, $request->idProducto]);
    } else {
        // Creo
        $db->set("insert into catalogo (preciounitario, proveedorcod, productocod) values(?, ?, ?);", [$request->precio, $request->idUsuario, $request->idProducto]);
    }
}