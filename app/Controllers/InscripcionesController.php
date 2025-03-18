<?php

namespace App\Controllers;

use App\Models\Inscripciones;

require_once __DIR__ . '/../lib/DecodificarToken.php';
class InscripcionesController
{
    private $requestMethod;
    private $inscripcionesId;
    private $inscripciones;


    public function __construct($requestMethod, $inscripcionesId)
    {
        $this->requestMethod = $requestMethod;
        $this->inscripcionesId = $inscripcionesId;
        $this->inscripciones = new Inscripciones();
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getAllInscripciones();
                break;
            case 'POST':
                $response = $this->createInscripcionesFromRequest();
                break;
            case 'PUT':
                $response = $this->updateInscripcionesFromRequest($this->inscripcionesId);
                break;
            case 'DELETE':
                $response = $this->deleteInscripciones($this->inscripcionesId);
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

    private function getAllInscripciones()
    {
        $result = $this->inscripciones->getInscripcionesByUserId(decodificarToken());
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createInscripcionesFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateInscripciones($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->inscripciones->set($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateInscripcionesFromRequest($id)
    {
        $result = $this->inscripciones->get($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateInscripciones($input)) {
            return $this->unprocessableEntityResponse();
        }
        if ($result['usuario_id'] != decodificarToken()) {
            return $this->unprocessableEntityResponse();
        }
        $this->inscripciones->edit($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteInscripciones($id)
    {
        $result = $this->inscripciones->get($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        if ($result['usuario_id'] != decodificarToken()) {
            return $this->unprocessableEntityResponse();
        }
        $this->inscripciones->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateInscripciones($input)
    {
        if (!isset($input['nom_solicitante']) || !isset($input['telefono']) || !isset($input['correo']) || !isset($input['actividades_id']) || !isset($input['fecha_incripcion'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode(array(
            "message" => "Datos no validos"
        ));
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode(array(
            "message" => "No se encontro el recurso solicitado"
        ));
        return $response;
    }
}
