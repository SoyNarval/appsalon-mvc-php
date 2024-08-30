<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarEmail(){

        // Crear el objeto de email
        
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = $_ENV['EMAIL_HOST'];
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $_ENV['EMAIL_PORT'];
        $phpmailer->Username = $_ENV['EMAIL_USER'];
        $phpmailer->Password = $_ENV['EMAIL_PASS'];

        $phpmailer->setFrom('cuentas@salon.com');
        $phpmailer->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $phpmailer->Subject = 'Confirmacion de cuenta';

        // Contenido del mail (Hay que hacerlo en html)

        $phpmailer->isHTML(TRUE);
        $phpmailer->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre ."</strong>, para completar tu cuenta confirma tu correo entrando en el siguente enlace. </p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/confirmar?token=" . $this->token . "'> Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si no solicitaste la cuenta puedes ignorar este mensaje</p>";
        $contenido .= "</html>";

        $phpmailer->Body = $contenido;

        // Enviar el EMAIL

        $phpmailer->send();
    }
        
    public function enviarInstrucciones(){

         // Crear el objeto de email
        
            $phpmailer = new PHPMailer();
            $phpmailer->isSMTP();
            $phpmailer->Host = $_ENV['EMAIL_HOST'];
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = $_ENV['EMAIL_PORT'];
            $phpmailer->Username = $_ENV['EMAIL_USER'];
            $phpmailer->Password = $_ENV['EMAIL__PASS'];
    
            $phpmailer->setFrom('cuentas@salon.com');
            $phpmailer->addAddress('cuentas@appsalon.com', 'AppSalon.com');
            $phpmailer->Subject = 'Recuperar Contraseña';
    
            // Contenido del mail (Hay que hacerlo en html)
    
            $phpmailer->isHTML(TRUE);
            $phpmailer->CharSet = 'UTF-8';
    
            $contenido = "<html>";
            $contenido .= "<p><strong>Hola " . $this->nombre ."</strong>, entra en el siguiente enlace para restablecer tu contraseña. </p>";
            $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $this->token . "'> Confirmar Cuenta</a></p>";
            $contenido .= "<p>Si no solicitaste recuperar tu contraseña puedes ignorar este mensaje</p>";
            $contenido .= "</html>";
    
            $phpmailer->Body = $contenido;
    
            // Enviar el EMAIL
    
            $phpmailer->send();

    }

}
