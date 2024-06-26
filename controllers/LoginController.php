<?php
namespace Controllers;

use Clases\Email;
use Model\Usuario;
use MVC\Router;


class LoginController{
    public static function login( Router $router ){
        $alertas = [];
        isOpen();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
                
            if(empty($alertas)){
                //comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);
                    
                if($usuario){
                        //Verificar el password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        // Autenticar ewl usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('location: /admin');
                        }else{
                            header('location: /cita');
                        };
                    };
                }else {
                        Usuario::setAlerta('error','Usuario no encontrado');
                };
                    
            };

            
        };

        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout(Router $router){
        $_SESSION = [];
        header('Location: /');
    }

    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = $auth::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1"){
                    //generar token de un solo uso
                    $usuario->crearToken();
                    $usuario->guardar();

                    // enviar el email
                    $email = new Email($usuario->email, $usuario->token, $usuario->nombre);
                    $email->enviarInstrucciones();

                    // Alerta de exito
                    Usuario::setAlerta('exito','Revisa tu email');
                }else{
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                };

            };

        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'alertas' => $alertas
        ]);
    }


    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){

            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        };

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Guardar nueva password
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)){
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();

                if($resultado){
                    header('location: /');
                };

            };

        };

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }


    public static function crear(Router $router){

        $usuario = new Usuario;

        //alertas vacias    
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alertas este vacio 
            if(empty($alertas)){

                //Revisar si el usuario ya existe
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    //HASHEAR password
                    $usuario->hashPassword();
                    //No esta registrado
                    
                    //Generar un token unico
                    $usuario->crearToken();

                    //Enviare el email
                    $email = new Email($usuario->email, $usuario->token, $usuario->nombre);
                    $email->enviarConfirmacion();

                    //Crear el usuario
                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('location: /mensaje ');
                    };
                };    
            };
        };

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas = [];

        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            //Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no valido');
        }else{
            //Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Usuario verificado correctamente');
        };

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',[
            'alertas'=>$alertas
        ]);
    }
};