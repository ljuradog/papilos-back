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
//error_log("Peticion ". print_r($peticion, true));

if ($requestType == "GET" || !is_null($peticion) ) {
    switch ($requestType) {
        case 'GET':
            $idUsuario = filter_input(INPUT_GET, 'idUsuario', FILTER_SANITIZE_URL);
            error_log("Peticion ". print_r($idUsuario, true));
            if (is_null($idUsuario)) {
                $response->productos = getCotizaciones($db);
            } else {
                $response->productos = getCotizacionesCliente($db, $idUsuario);    
            }

            break;
        case 'POST':
            crearCotizacion($db, $peticion);
            $response->resultado = 'OK';
            break;
    }
}

//RESPUESTA
echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);


// Trae las cotizaciones creadas por cliente
function getCotizaciones($db) {
    return $db->set("select cotizacion.*, count(productoscotizacion.prodcotizacod) items, DATE_PART('minute', feccierre - now() )
    from cotizacion
    join productoscotizacion on cotizacion.cotizacioncod = productoscotizacion.cotizacioncod
    group by cotizacion.cotizacioncod");
}

// Trae las cotizaciones creadas por cliente
function getCotizacionesCliente($db, $idUsuario) {
    return $db->set("select cotizacion.*, count(productoscotizacion.prodcotizacod) items, DATE_PART('minute', feccierre - now() ) tiempo
    from cotizacion
    join productoscotizacion on cotizacion.cotizacioncod = productoscotizacion.cotizacioncod
    where usuariocod = ?
    group by cotizacion.cotizacioncod", [$idUsuario]);
}

// Actualiza los precios
function crearCotizacion($db, $request) {
    // Crear encabezado cotizacion
    $automatico = $request->tiempo == '0' ? 'true' : 'false';

    $db->set("insert into cotizacion (usuariocod, automatico, feccierre) values(?, ?, now() + interval '". $request->tiempo." min');", [$request->idUsuario, $automatico], 'cotizacion_cotizacioncod_seq');
    $cotizacioncod = $db->getLastId();

    foreach ($request->productos as $producto) {
        $db->set("insert into productoscotizacion (productocod, cotizacioncod, cantidad) values(?, ?, ?);", [$producto->productocod, $cotizacioncod, $producto->cantidad], 'productoscotizacion_prodcotizacod_seq');
    }
}