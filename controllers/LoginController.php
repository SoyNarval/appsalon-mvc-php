<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Classes\Email;


class LoginController{

    public static function login(Router $router){
        $alertas = [];

        $auth = new Usuario;


        if($_SERVER['REQUEST_METHOD']==='POST'){

            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                // Comprobar que exista
                $usuario = Usuario::where('email', $auth->email);
                if($usuario){
                    // Verificar Contraseña
                    
                    if($usuario->comprobarContraseñaVerificado($auth->contraseña)){
                        // Autenticar al usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " .  $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;


                        // Redireccionamiento

                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header("Location: /admin");
                        }else{
                            header("Location: /cita");
                        }

                        
                    }
                } else{
                Usuario::setAlerta('error', 'Usuario no encontrado, comprueba el correo y la contraseña');
                }
            }
        }

        $alertas =  Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario(($_POST));
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado){

                    // Generar token nuevo

                    $usuario->crearToken();
                    $usuario->guardar();

                    //Enviar el email

                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();


                    Usuario::setAlerta('exito', 'Te hemos enviado un mail de confirmación');
                }else{
                    Usuario::setAlerta('error', 'Usuario no registrado o no confirmado');
                }
                
        $alertas = Usuario::getAlertas();
            }
        }

        $router->render('auth/olvide-contraseña',[
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){
        
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);

        // Buscar usuario por su Token

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no Válido');
            $error = true;
            
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // Leer la nueva contraseña

            $contraseña = new Usuario($_POST);
            $alertas = $contraseña->validarContraseña();
            if(empty($alertas)){
                $usuario->contraseña = null;
                
                $usuario->contraseña = $contraseña->contraseña;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if($resultado){
                    Usuario::setAlerta('exito', 'Contraseña restablecida');
                }
            }
        }

        $alertas = Usuario::getAlertas();


        $router->render('auth/recuperar', [
            'alertas'=>$alertas,
            'error' => $error
        ]);
        
    }

    public static function crear(Router $router){

        // Alertas vacias
        $alertas = [];

        $usuario =new Usuario();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alertas este vacio
            if(empty($alertas)){
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    // Hashear contraseña

                    $usuario->hashPassword();

                    // Generar Token Unico

                    $usuario->crearToken();


                    // Enviar el mail

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarEmail();

                    // Crear el Usuario

                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /mensaje');
                    }

                    
                }
            }
        }

        $router->render('auth/crear-cuenta',[
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function confirmar(Router $router){
        $alertas = [];

        $token = ($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            // Mostrar mensaje de ERROR
            Usuario::setAlerta('error', 'Token no Válido');

        }else {
            // Modificar a usuario confirmado
            Usuario::setAlerta('exito', 'Usuario Confirmado');
        
            $usuario->confirmado = "1";
            $usuario->token= null;
            $usuario->guardar();
            
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }
}


?>