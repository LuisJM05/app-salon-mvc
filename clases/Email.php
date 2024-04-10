<?php

namespace Clases;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $token;
    public $nombre;

    public function __construct($email, $token, $nombre){
        $this->email = $email;
        $this->token = $token;
        $this->nombre = $nombre;
    }

    public function enviarConfirmacion(){
        $mail = new PHPMailer();

        //configurar SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->Port = $_ENV['EMAIL_PORT'];

        //configurar el contenido del email
        $mail->setFrom('cuenta@appsalon.com');

        $mail->addAddress('ljm08358@gmail.com','AppSalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        //Habilitar HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        // Definir el contenido
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre ."</strong> Has creado tu cuenta en AppSalon, solo debes confirmarla presionando en el siguiente enlace<p>" ;
        $contenido .= "<p>Presiona aqui: <a href='". $_ENV['APP_URL'] ."/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>" ;
        $contenido .= "<p>Si tu no solicitaste el codigo, puedes ignorar el mensaje.</p>" ;
        $contenido .= "</html>";

        $mail->Body = $contenido;
        $mail->AltBody = 'Esto e texto alternativo sin HTML';

        //Enviar el email
        $mail->send();

        // if($mail->send()){
        //     $mensaje = 'Mensaje Enviado Correctamente';
        // }else {
        //     $mensaje = 'El mensaje no se pudo enviar';
        // };

    }

    public function enviarInstrucciones(){
        $mail = new PHPMailer();

         //configurar SMTP
         $mail->isSMTP();
         $mail->Host = $_ENV['EMAIL_HOST'];
         $mail->SMTPAuth = true;
         $mail->Username = $_ENV['EMAIL_USER'];
         $mail->Password = $_ENV['EMAIL_PASS'];
         $mail->Port = $_ENV['EMAIL_PORT'];

        //configurar el contenido del email
        $mail->setFrom('cuenta@appsalon.com');

        $mail->addAddress('cuenta@appsalon.com','AppSalon.com');
        $mail->Subject = 'Restablece tu contraseña';

        //Habilitar HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        // Definir el contenido
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre ."</strong> Has solicitado restablecer tu contraseña, sigue el siguiente enlace para hacerlo<p>" ;
        $contenido .= "<p>Presiona aqui: <a href='". $_ENV['APP_URL'] ."/recuperar?token=" . $this->token . "'>Restablecer contraseña</a></p>" ;
        $contenido .= "<p>Si tu no solicitaste el codigo, puedes ignorar el mensaje.</p>" ;
        $contenido .= "</html>";

        $mail->Body = $contenido;
        $mail->AltBody = 'Esto e texto alternativo sin HTML';

        //Enviar el email
        $mail->send();

    }
};