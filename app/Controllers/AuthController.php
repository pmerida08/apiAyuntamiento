<?php

namespace App\Controllers;

use \Firebase\JWT\JWT;
use \Firebase\JWT\key;
use App\Models\Users;

class AuthController
{
    private $requestMethod;
    private $userId;
    private $users;
    public function __construct($requestMethod)
    {
        $this->requestMethod = $requestMethod;
        $this->users = Users::getInstancia();
    }
    
    public function loginFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $usuario = $input['email'];
        $password = $input['password'];
        $dataUser = $this->users->login($usuario, $password);
        $usuarioId = $this->users->getIdByEmail($usuario);

        if ($dataUser) {
            $key = KEY;
            $issuer_claim = "http://ayuntapi.local";
            $audience_claim = "http://ayuntapi.local";
            $issuedat_claim = time();
            $notbefore_claim = time();
            $expire_claim = $issuedat_claim + 3600;
            $token = array(
                "iss" => $issuer_claim,
                "aud" => $audience_claim,
                "iat" => $issuedat_claim,
                "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => array(
                    "usuarioId" => $usuarioId
                )
            );

            $jwt = JWT::encode($token, $key, 'HS256');
            $res = json_encode(
                array(
                    "message" => "Login correcto",
                    "jwt" => $jwt,
                    "email" => $usuario,
                    "expireAt" => $expire_claim,
                    "userId" => $usuarioId
                )
            );

            $response['status_code_header'] = 'HTTP/1.1 201 CREATED';
            $response['body'] = $res;
        } else {
            $response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
            $response['body'] = null;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
            return true;
        }
    }


    public function registerFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $nombre = $input['nombre'];
        $email = $input['email'];
        $password = $input['password'];
        $this->users->register($nombre, $email, $password);
        $response['status_code_header'] = 'HTTP/1.1 201 CREATED';
        $response['body'] = json_encode(array("message" => "Usuario añadido"));
        header($response['status_code_header']);
        echo $response['body'];
    }

    public function unprocessableEntityResponse($message = null)
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode(["message" => $message]);
        return $response;
    }
}
