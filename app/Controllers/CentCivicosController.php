<?php

namespace App\Controllers;

use App\Models\CentCivicos;

class CentCivicosController
{
    #Propiedades de la clase
    private $requestMethod; //Metodo http
    private $centCivicosId; //Id del centro civico
    private $centCivicos; //Centros Civicos
    #Constructor. Necesita Petición y id del centro civico
    public function __construct($requestMethod, $centCivicosId)
    {
        $this->requestMethod = $requestMethod;
        $this->centCivicosId = $centCivicosId;
        $this->centCivicos = new CentCivicos();
    }

    /**
     * Función que procesa la petición
     */
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->centCivicosId) {
                    $response = $this->getCentCivicos($this->centCivicosId);
                } else {
                    $response = $this->getAllCentCivicos();
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
    /**
     * Funcion que se encarfa de conectar con el modelo para recuperar la información.
     * @input: El id del centro civico que queremos recuperar.
     * @return: La información del centro civico en formato JSON.
     */
    private function getCentCivicos($id)
    {
        $result = $this->centCivicos->get($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    /**
     * Funcion que se encarfa de conectar con el modelo para recuperar la información.
     * @return: La información de todos los centros civicos en formato JSON.
     */

    private function getAllCentCivicos(){
        $result = $this->centCivicos->getAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function notFoundResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}
