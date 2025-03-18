<?php

namespace App\Controllers;

use App\Models\Reservas;

require_once __DIR__ . '/../lib/DecodificarToken.php';

class ReservasController
{
    private $requestMethod;
    private $reservasId;
    private $reservas;

    public function __construct($requestMethod, $reservasId)
    {
        $this->requestMethod = $requestMethod;
        $this->reservasId = $reservasId;
        $this->reservas = new Reservas();
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getAllReservas();
                break;
            case 'POST':
                $response = $this->createReservasFromRequest();
                break;
            case 'PUT':
                $response = $this->updateReservasFromRequest($this->reservasId);
                break;
            case 'DELETE':
                $response = $this->deleteReservas($this->reservasId);
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

    private function getAllReservas()
    {
        $result = $this->reservas->getReservasByUserId(decodificarToken());
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createReservasFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateReservas($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->reservas->set($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateReservasFromRequest($id)
    {
        $result = $this->reservas->get($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateReservas($input)) {
            return $this->unprocessableEntityResponse();
        }
        if ($result['usuario_id'] != decodificarToken()) {
            return $this->unprocessableEntityResponse();
        }
        $this->reservas->edit($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteReservas($id)
    {
        $result = $this->reservas->get($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        if ($result['usuario_id'] != decodificarToken()) {
            return $this->unprocessableEntityResponse();
        }
        $this->reservas->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode(['error' => 'Invalid input']);
        return $response;
    }

    private function validateReservas($input)
    {
        if (!isset($input['nom_solicitante']) || !isset($input['telefono']) || !isset($input['correo']) || !isset($input['instalaciones_id']) || !isset($input['fechahora_inicio'])) {
            return false;
        }
        return true;
    }
}
