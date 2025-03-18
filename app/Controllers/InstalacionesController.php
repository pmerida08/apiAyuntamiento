<?php

namespace App\Controllers;

use App\Models\Instalaciones;

class InstalacionesController
{
    private $requestMethod;
    private $instalacionesId;
    private $instalaciones;

    public function __construct($requestMethod, $instalacionesId)
    {
        $this->requestMethod = $requestMethod;
        $this->instalacionesId = $instalacionesId;
        $this->instalaciones = new Instalaciones();
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->instalacionesId) {
                    $response = $this->getInstalaciones($this->instalacionesId);
                } else {
                    $response = $this->getAllInstalaciones();
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

    private function getInstalaciones($id)
    {
        $result = $this->instalaciones->getByCentrosCivicosId($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getAllInstalaciones()
    {
        $result = $this->instalaciones->getAll();
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
