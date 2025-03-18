<?php

namespace App\Controllers;

use App\Models\Actividades;

class ActividadesController
{
    private $requestMethod;
    private $actividadesId;
    private $actividades;

    public function __construct($requestMethod, $actividadesId)
    {
        $this->requestMethod = $requestMethod;
        $this->actividadesId = $actividadesId;
        $this->actividades = new Actividades();
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->actividadesId) {
                    $response = $this->getactividades($this->actividadesId);
                } else {
                    $response = $this->getAllActividades();
                };
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

    private function getActividades($id)
    {
        $result = $this->actividades->getByCentrosCivicosId($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getAllActividades()
    {
        $result = $this->actividades->getAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}