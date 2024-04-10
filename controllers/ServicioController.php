<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController{
    public static function index( Router $router ){
        session_start();

        isAdmin();

        $servicios = Servicio::all();

        $router->render('/servicios/index',[
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear( Router $router ){
        session_start();

        isAdmin();

        $servicio = new Servicio;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                header('location: /servicios');
            };

        };

        $router->render('/servicios/crear',[
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar( Router $router ){
        session_start();

        isAdmin();

        $id = $_GET['id'];

        if(!is_numeric($id)){
            header('location: /admin');
        };

        $servicios = Servicio::find($id);
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $servicios->sincronizar($_POST);

            $alertas = $servicios->validar();

            if(empty($alertas)){
                $servicios->guardar();
                header('location: /servicios');
            };
        };

        $router->render('/servicios/actualizar',[
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicios,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar( Router $router ){
        session_start();

        isAdmin();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];

            $cita = Servicio::find($id);
            $cita->eliminar();

            header('Location: /servicios');
        };
    }
};