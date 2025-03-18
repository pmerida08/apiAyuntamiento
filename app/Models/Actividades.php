<?php

namespace App\Models;

require_once("DBAbstractModel.php");

class Actividades extends DBAbstractModel
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
        $this->query = "INSERT INTO actividades(centcivicos_id,nombre,descripcion,fecha_inicio,fecha_final,horario,plaza) VALUES(:centcivicos_id, :nombre, :descripcion, :fecha_inicio, :fecha_final, :horario, :plaza)";
        $this->parametros['centcivicos_id'] = $centcivicos_id;
        $this->parametros['nombre'] = $nombre;
        $this->parametros['descripcion'] = $descripcion;
        $this->parametros['fecha_inicio'] = $fecha_inicio;
        $this->parametros['fecha_final'] = $fecha_final;
        $this->parametros['horario'] = $horario;
        $this->parametros['plaza'] = $plaza;
        $this->get_results_from_query();
        $this->mensaje = 'SH añadido';
    }

    public function get($id = "") // funciona
    {
        if ($id != '') {
            $this->query = "SELECT * FROM actividades WHERE id = :id";
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
        if ($id != '') {
            foreach ($user_data as $campo => $valor) {
                $$campo = $valor;
            }
            $this->query = "UPDATE actividades SET centcivicos_id=:centcivicos_id, nombre=:nombre, descripcion=:descripcion, fecha_inicio=:fecha_inicio, fecha_final=:fecha_final, horario=:horario, plaza=:plaza WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->parametros['centcivicos_id'] = $centcivicos_id;
            $this->parametros['nombre'] = $nombre;
            $this->parametros['descripcion'] = $descripcion;
            $this->parametros['fecha_inicio'] = $fecha_inicio;
            $this->parametros['fecha_final'] = $fecha_final;
            $this->parametros['horario'] = $horario;
            $this->parametros['plaza'] = $plaza;
            $this->get_results_from_query();
            $this->mensaje = 'sh modificado';
        }
    }

    public function delete($id = "")
    {
        if ($id != '') {
            $this->query = "DELETE FROM actividades WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
            $this->mensaje = 'sh eliminado';
        }
    }

    public function getAll()
    {
        $this->query = "SELECT * FROM actividades";
        $this->get_results_from_query();

        if (count($this->rows) >= 1) {
            return $this->rows;
        } else {
            return [];
        }
    }

    public function getByCentrosCivicosId($id = "")
    {
        if ($id != '') {
            $this->query = "SELECT * FROM actividades WHERE centcivicos_id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
        }
        if (count($this->rows) >= 1) {
            return $this->rows;
        } else {
            return [];
        }
    }
}