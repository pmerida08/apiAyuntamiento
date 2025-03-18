<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function decodificarToken()
{
    // Verificar si el token JWT está presente
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return null;
    }
    $authHeader = $_SERVER['HTTP_AUTHORIZATION']; // Obtener el encabezado de autorización
    $arr = explode(" ", $authHeader); // Separar el encabezado en un arreglo
    $jwt = $arr[1]; // Obtener el token JWT
    // Verificar si el token JWT está presente
    if (!$jwt) {
        return null;
    }

    // Decodificar el token JWT
    try {
        $decoded = JWT::decode($jwt, new Key(KEY, 'HS256'));
        // Log o imprimir el token decodificado para ver la estructura
        // echo json_encode($decoded);
        $idUser = $decoded->data->userId;
        return $idUser;
    } catch (Exception $e) {
        return null;
    }
    
}
