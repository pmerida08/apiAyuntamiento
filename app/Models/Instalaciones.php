<?php

namespace App\Models;

require_once("DBAbstractModel.php");

class Instalaciones extends DBAbstractModel
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
        echo 'entra a bd';
        foreach ($data as $campo => $valor) {
            $$campo = $valor;
        }
        $this->query = "INSERT INTO instalaciones(nombre,descripcion,centcivicos_id,capacidad_max) VALUES(:nombre, :descripcion, :centcivicos_id, :capacidad_max)";
        $this->parametros['nombre'] = $nombre;
        $this->parametros['descripcion'] = $descripcion;
        $this->parametros['centcivicos_id'] = $centcivicos_id;
        $this->parametros['capacidad_max'] = $capacidad_max;
        $this->get_results_from_query();
        $this->mensaje = 'SH añadido';
    }

    public function get($id = "") // funciona
    {
        if ($id != '') {
            $this->query = "SELECT * FROM instalaciones WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
        }
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
            $this->mensaje = 'sh encontrado';
            return $this->rows[0];
        } else {
            $this->mensaje = 'sh no encontrado';
            return [];
        }
    }

    public function edit($id = "", $user_data = []) // funciona
    {
        foreach ($user_data as $campo => $valor) {
            $$campo = $valor;
        }
        $this->query = "UPDATE instalaciones SET nombre=:nombre, descripcion=:descripcion, centcivicos_id=:centcivicos_id, capacidad_max=:capacidad_max WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->parametros['nombre'] = $nombre;
        $this->parametros['descripcion'] = $descripcion;
        $this->parametros['centcivicos_id'] = $centcivicos_id;
        $this->parametros['capacidad_max'] = $capacidad_max;
        $this->get_results_from_query();
        $this->mensaje = 'sh modificado';
    }

    public function delete($id = "")
    {
        $this->query = "DELETE FROM instalaciones WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        $this->mensaje = 'sh eliminado';
    }

    public function getAll()
    {
        $this->query = "SELECT * FROM instalaciones";
        $this->get_results_from_query();

        if (count($this->rows) >= 1) {
            $this->mensaje = 'sh encontrado';
            return $this->rows;
        } else {
            $this->mensaje = 'sh no encontrado';
            return [];
        }
    }

    public function getByCentrosCivicosId($id = "")
    {
        if ($id != '') {
            $this->query = "SELECT * FROM instalaciones WHERE centcivicos_id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
        }
        if (count($this->rows) >= 1) {
            $this->mensaje = 'sh encontrado';
            return $this->rows;
        } else {
            $this->mensaje = 'sh no encontrado';
            return [];
        }
    }
}
