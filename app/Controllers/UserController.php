<?php

namespace App\Controllers;

require_once __DIR__ . '/../lib/DecodificarToken.php';

use App\Models\Users;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController
{
    private $requestMethod;
    private $userId;
    private $user;

    public function __construct($requestMethod, $userId)
    {
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
        $this->user = Users::getInstancia();
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getUser();
                break;
            case 'PUT':
                $response = $this->updateUser();
                break;
            case 'DELETE':
                $response = $this->deleteUser();
                break;
            case 'POST':
                if ($_SERVER['REQUEST_URI'] === '/register/') {
                    $response = $this->register();
                } else
                if ($_SERVER['REQUEST_URI'] === '/login/') {
                    $response = $this->login();
                } elseif ($_SERVER['REQUEST_URI'] === '/token/refresh') {
                    $response = $this->tokenRefresh();
                }
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    public function tokenRefresh()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $token = JWT::encode(["data" => ["userId" => $data['userId'], "role" => "usuario"]], KEY, 'HS256');
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["token" => $token]);
        return $response;
    }

    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->user->login($data['email'], $data['password']);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $token = JWT::encode(["data" => ["userId" => $result['id'], "role" => "usuario"]], KEY, 'HS256');

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["token" => $token]);
        return $response;
    }



    public function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->user->register($data['nombre'], $data['email'], $data['password']);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode(["message" => "Usuario registrado"]);
        return $response;
    }

    public function getUser()
    {
        $usuarioId = decodificarToken();
        $result = $this->user->get($usuarioId);
        // var_dump($result);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    public function updateUser()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $usuarioId = decodificarToken();
        $this->user->edit($data, $usuarioId);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["message" => "Usuario actualizado"]);
        return $response;
    }

    public function deleteUser()
    {
        $usuarioId = decodificarToken();
        $this->user->delete($usuarioId);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["message" => "Usuario eliminado"]);
        return $response;
    }

    public function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode(["message" => "Usuario no encontrado"]);
        return $response;
    }
}
