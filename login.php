<?php

$response = new stdClass;
$response->mensaje = "Hola leo, has iniciado sesion con exito!";
$response->user->password = null;
$response->user->username = 'leo';
$response->user->authorities = [[
    //'authority' => "ROLE_CLIENTE"
    'authority' => "ROLE_PROVEEDOR"
]];
$response->user->password = null;
$response->user->password = null;

$response->user->accountNonExpired = true;
$response->user->accountNonLocked = true;
$response->user->credentialsNonExpired = true;
$response->user->enabled = true;
$response->token = "eyJhbGciOiJIUzUxMiJ9.eyJhdXRob3JpdGllcyI6Ilt7XCJhdXRob3JpdHlcIjpcIlJPTEVfQ0xJRU5URVwifV0iLCJzdWIiOiJsZWlkeSIsImlhdCI6MTU1NTcxMTAzNCwiZXhwIjoxNTU1NzI1MDM0fQ.9QchcrsrwF7f_Ihy6Kb4tzJpayV0ZzbuDKaQwUT5N0rShLVnEEWvz6aQvNm-gmMcCy7ePpnJertnO_8iNbuwBQ";

//RESPUESTA
echo json_encode($response,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
