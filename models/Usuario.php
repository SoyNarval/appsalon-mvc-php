<?php

namespace Model;

class Usuario extends ActiveRecord{

    // Base de Datos

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'contraseña', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $contraseña;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->contraseña = $args['contraseña'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? 0;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
    }

    // Validar el Log in

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es Obligatorio';
        }if(!$this->contraseña){
            self::$alertas['error'][] = 'La contraseña es Obligatoria';
        }
        return self::$alertas;
    }

    // Validar email cuando olvida contraseña

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es Obligatorio';
            
        
        }
        return self::$alertas;
    }

    // Mensaje de Validacion para la Creacion

    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre es Obligatorio';
        }
        if(!$this->apellido){
            self::$alertas['error'][] = 'El apellido es Obligatorio';
        }
        if(!$this->telefono){
            self::$alertas['error'][] = 'El telefono es Obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El correo es Obligatorio';
        }
        if(!$this->contraseña){
            self::$alertas['error'][] = 'La contrasñea es Obligatoria';
        }
        if(strlen($this->contraseña) > 6){
            self:$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    // Validar nueva contraseña

    public function validarContraseña(){
        if(!$this->contraseña){
            self::$alertas['error'][] = 'La Contraseña es obligatoria';
        }if(strlen($this->contraseña) < 6){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    // Revisar si el Usuario existe

    public function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
    
        $resultado = self::$db->query($query);
        
        if($resultado->num_rows){
            self::$alertas['error'][] = 'El Usuario ya esta registrado';
        }

        return $resultado;
    }

    // Hashear Contraseña

    public function hashPassword(){
        $this->contraseña = password_hash($this->contraseña, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid();
    }

    public function comprobarContraseñaVerificado($contraseña){
        
        $resultado = password_verify($contraseña, $this->contraseña);
        
        if(!$resultado || !$this->confirmado){
            self::$alertas['error'][] = 'Contraseña incorrecta o usuario sin verificar';
        }else{
            return true;
        }
    }
}