<?php

header("Access-Control-Allow-Origin: *"); 

$response = new stdClass;
$response->productos = [
    [
        'idProducto' => "1",
        'descripcion' => "Lápiz Negro",
        'marca' => "Faber Castel",
        'precio' => "2000",
    ],
    [
        'idProducto' => "2",
        'descripcion' => "Lápiz Rojo",
        'marca' => "Faber Castel",
        'precio' => "",
    ],
    [
        'idProducto' => "3",
        'descripcion' => "Fotocopias",
        'marca' => "",
        'precio' => "2000",
    ],
    [
        'idProducto' => "4",
        'descripcion' => "Colores",
        'marca' => "Faber Castel",
        'precio' => "2000",
    ],
    [
        'idProducto' => "5",
        'descripcion' => "Resma de Papel",
        'marca' => "",
    ],
];

//RESPUESTA
echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
