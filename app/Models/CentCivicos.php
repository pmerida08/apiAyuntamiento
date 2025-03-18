<?php

namespace App\Models;

require_once("DBAbstractModel.php");

class CentCivicos extends DBAbstractModel
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
        $this->query = "INSERT INTO centcivicos(nombre,direccion,tel_contacto,horario, foto) VALUES(:nombre, :direccion, :tel_contacto, :horario, :foto)";
        $this->parametros['nombre'] = $nombre;
        $this->parametros['direccion'] = $direccion;
        $this->parametros['tel_contacto'] = $tel_contacto;
        $this->parametros['horario'] = $horario;
        $this->parametros['foto'] = $foto;
        $this->get_results_from_query();
        $this->mensaje = 'SH añadido';
    }

    public function get($id = "") // funciona
    {
        if ($id != '') {
            $this->query = "SELECT * FROM centcivicos WHERE id = :id";
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
        $fecha = new \DateTime();
        foreach ($user_data as $campo => $valor) {
            $$campo = $valor;
        }
        $this->query = "UPDATE centcivicos SET nombre=:nombre, direccion=:direccion, tel_contacto=:tel_contacto, horario=:horario, foto=:foto WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->parametros['nombre'] = $nombre;
        $this->parametros['direccion'] = $direccion;
        $this->parametros['tel_contacto'] = $tel_contacto;
        $this->parametros['horario'] = $horario;
        $this->parametros['foto'] = $foto;
        $this->get_results_from_query();
        $this->mensaje = 'sh modificado';
    }

    public function delete($id = "")
    {
        $this->query = "DELETE FROM centcivicos WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        $this->mensaje = 'sh eliminado';
    }

    public function getAll()
    {
        $this->query = "SELECT * FROM centcivicos";
        $this->get_results_from_query();
        return $this->rows;
    }

}