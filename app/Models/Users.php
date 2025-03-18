<?php

namespace App\Models;

class Users extends DBAbstractModel
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
    public function login($email, $password)
    {
        $this->query = "SELECT * FROM usuarios WHERE email = :email AND password = :password";
        $this->parametros['email'] = $email;
        $this->parametros['password'] = $password;

        $this->get_results_from_query();
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
            $this->mensaje = 'Usuario no encontrado';
        }


        return $this->rows[0] ?? null;
    }

    public function register($nombre, $email, $password)
    {
        $this->query = "INSERT INTO usuarios(nombre, email, password) VALUES(:nombre, :email, :password)";
        $this->parametros['nombre'] = $nombre;
        $this->parametros['email'] = $email;
        $this->parametros['password'] = $password;
        $this->get_results_from_query();
        $this->mensaje = 'Usuario añadido';
    }



    public function get($id = '')
    {
        if ($id != '') {
            $this->query = "SELECT * FROM usuarios WHERE id = :id";
            $this->parametros['id'] = $id;
            $this->get_results_from_query();
        }
        if (count($this->rows) == 1) {
            foreach ($this->rows[0] as $propiedad => $valor) {
                $this->$propiedad = $valor;
            }
            $this->mensaje = 'Usuario encontrado';
        } else {
            $this->mensaje = 'Usuario no encontrado';
        }
        return $this->rows;
    }

    public function set() {}
    public function edit($campos = [], $idUser = "")
    {
        $this->query = "UPDATE usuarios SET ";
        foreach ($campos as $campo => $valor) {
            $this->query .= "$campo = :$campo, ";
            $this->parametros[$campo] = $valor;
        }
        $this->query = substr($this->query, 0, -2);
        $this->query .= " WHERE id = :id";
        $this->parametros['id'] = $idUser;
        $this->get_results_from_query();
        if ($this->affected_rows != 1) {
            $this->mensaje = 'Usuario no actualizado';
            return false;
        }
        $this->mensaje = 'Usuario actualizado';
        return true;
    }
    public function delete($id = "")
    {
        $this->query = "DELETE FROM usuarios WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
        $this->mensaje = 'Usuario eliminado';
    }

    public function getAll()
    {
        $this->query = "SELECT * FROM usuarios";
        $this->get_results_from_query();
        return $this->rows;
    }

    public function getIdByEmail($email)
    {
        $this->query = "SELECT id FROM usuarios WHERE email = :email";
        $this->parametros['email'] = $email;
        $this->get_results_from_query();
        return $this->rows[0]['id'];
    }

}
