<?php
namespace Controllers;

use MVC\Router;
use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;


class APIcontroller {
    public static function index(Router $router){
        $servicios = Servicio::all();

        echo json_encode($servicios);

        //$router->render('',[
        //    'servicios' => $servicios
        //]);
    }

    public static function guardar(){
      
        //Almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        //Almacena la cita y el servicio
        $id = $resultado['id'];
        

        //Almacena los servicios con id de la cita
        $idServicios = explode(',', $_POST['servicios']);

        foreach($idServicios as $idServicio){
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];

            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        };

        //Retornamos una respuesta
        $respuesta = [
            'resultado' => $resultado
        ];

        echo json_encode($respuesta);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];

            $cita = Cita::find($id);
            $cita->eliminar();

            header('Location:' . $_SERVER['HTTP_REFERER']);
        };
    }
};