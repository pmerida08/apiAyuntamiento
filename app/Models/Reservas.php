<?php

namespace App\Models;

require_once("DBAbstractModel.php");

require_once __DIR__ . '/../lib/DecodificarToken.php';

class Reservas extends DBAbstractModel
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
        $this->query = "INSERT INTO reservas(nom_solicitante,telefono,correo,instalaciones_id,fechahora_inicio, fechahora_final, estado, usuario_id) VALUES(:nom_solicitante, :telefono, :correo, :instalaciones_id, :fechahora_inicio, :fechahora_final, :estado, :usuario_id)";
        $this->parametros['nom_solicitante'] = $nom_solicitante;
        $this->parametros['telefono'] = $telefono;
        $this->parametros['correo'] = $correo;
        $this->parametros['instalaciones_id'] = $instalaciones_id;
        $this->parametros['fechahora_inicio'] = $fechahora_inicio;
        $this->parametros['fechahora_final'] = $fechahora_final;
        $this->parametros['estado'] = 'pendiente';
        $this->parametros['usuario_id'] = decodificarToken();
        $this->get_results_from_query();
        $this->mensaje = 'SH añadido';
    }

    public function get($id = "") // funciona
    {
        if ($id != '') {
            $this->query = "SELECT * FROM reservas WHERE id = :id";
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
            $this->query = "UPDATE reservas SET nom_solicitante = :nom_solicitante, telefono = :telefono, correo = :correo, instalaciones_id = :instalaciones_id, fechahora_inicio = :fechahora_inicio, fechahora_final = :fechahora_final, estado = :estado WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->parametros['nom_solicitante'] = $nom_solicitante;
            $this->parametros['telefono'] = $telefono;
            $this->parametros['correo'] = $correo;
            $this->parametros['instalaciones_id'] = $instalaciones_id;
            $this->parametros['fechahora_inicio'] = $fechahora_inicio;
            $this->parametros['fechahora_final'] = $fechahora_final;
            $this->parametros['estado'] = $estado;
            $this->get_results_from_query();
            $this->mensaje = 'sh modificado';
        }
    }

    public function delete($id = "")
    {
        $this->query = "DELETE FROM reservas WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        $this->mensaje = 'sh eliminado';
    }

    public function getAllReservas()
    {
        $this->query = "SELECT * FROM reservas";
        $this->get_results_from_query();
        return $this->rows;
    }

    public function getReservasByUserId($id = "")
    {
        if ($id != '') {
            $this->query = "SELECT * FROM reservas WHERE usuario_id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
        }
        return $this->rows;
    }
}
