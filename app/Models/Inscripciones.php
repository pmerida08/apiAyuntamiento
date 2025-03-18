<?php

namespace App\Models;

require_once("DBAbstractModel.php");

require_once __DIR__ . '/../lib/DecodificarToken.php';

class Inscripciones extends DBAbstractModel
{
    //Singleton
    private static $instancia;
    public static function getInstancia()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function __clone()
    {
        trigger_error("La clonación no está permitida.", E_USER_ERROR);
    }

    public function set($data = array()) // funciona
    {
        foreach ($data as $campo => $valor) {
            $$campo = $valor;
        }
        $this->query = "INSERT INTO inscripciones(nom_solicitante,telefono,correo,actividades_id, fecha_incripcion, estado, usuario_id) VALUES(:nom_solicitante, :telefono, :correo, :actividades_id, :fecha_incripcion, :estado, :usuario_id)";
        $this->parametros['nom_solicitante'] = $nom_solicitante;
        $this->parametros['telefono'] = $telefono;
        $this->parametros['correo'] = $correo;
        $this->parametros['actividades_id'] = $actividades_id;
        $this->parametros['fecha_incripcion'] = $fecha_incripcion;
        $this->parametros['estado'] = 'pendiente';
        $this->parametros['usuario_id'] = decodificarToken();
        $this->get_results_from_query();
        $this->mensaje = 'Inscripción añadida';
    }

    public function get($id = "") // funciona
    {
        if ($id != '') {
            $this->query = "SELECT * FROM inscripciones WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
        }
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
            $this->mensaje = 'Inscripción encontrada';
            return $this->rows[0];
        } else {
            $this->mensaje = 'Inscripción no encontrada';
            return [];
        }
    }

    public function edit($id = "", $user_data = []) // funciona
    {
        foreach ($user_data as $campo => $valor) {
            $$campo = $valor;
        }
        $this->query = "UPDATE inscripciones SET nom_solicitante = :nom_solicitante, telefono = :telefono, correo = :correo, actividades_id = :actividades_id, fecha_incripcion = :fecha_incripcion, estado = :estado WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->parametros['nom_solicitante'] = $nom_solicitante;
        $this->parametros['telefono'] = $telefono;
        $this->parametros['correo'] = $correo;
        $this->parametros['actividades_id'] = $actividades_id;
        $this->parametros['fecha_incripcion'] = $fecha_incripcion;
        $this->parametros['estado'] = 'pendiente';
        $this->get_results_from_query();
        $this->mensaje = 'Inscripción modificada';
    }

    public function delete($id = "")
    {
        $this->query = "DELETE FROM inscripciones WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        $this->mensaje = 'Inscripción eliminada';
    }

    public function getInscripciones($id = "")
    {
        if ($id != '') {
            $this->query = "SELECT * FROM inscripciones WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
        }
        if (count($this->rows) > 0) {
            $this->mensaje = 'Inscripciones encontradas';
            return $this->rows;
        } else {
            $this->mensaje = 'Inscripciones no encontradas';
            return [];
        }
    }

    public function getInscripcionesByUserId($id = "")
    {
        if ($id != '') {
            $this->query = "SELECT * FROM inscripciones WHERE usuario_id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
        }
        return count($this->rows) > 0 ? $this->rows : [];
    }
}
