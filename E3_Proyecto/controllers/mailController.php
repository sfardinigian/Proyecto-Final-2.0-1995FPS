<?php
require __DIR__ . '/../vendor/autoload.php';

use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

class MailController
{
    private $apiKey = 'd270478c72d558fbefa6e261c8962e2c';
    private $inboxId = 4164523;

    public function enviarCorreo($emailDestino, $asunto, $mensaje, $categoria = 'General')
    {
        $mailtrap = MailtrapClient::initSendingEmails(
            apiKey: $this->apiKey,
            inboxId: $this->inboxId,
            isSandbox: true,
        );

        // Creamos el correo
        $email = (new MailtrapEmail())
            ->from(new Address('noReply@cronos.com', 'Cronos'))
            ->to(new Address($emailDestino))
            ->subject($asunto)
            ->text($mensaje)
            ->category($categoria);

        // Enviamos mail
        $mailtrap->send($email);
    }

    // Mail al registrarse
    public function enviarMailRegistro($emailDestino, $nombreUsuario)
    {
        $asunto = '¡Registro exitoso!';
        $mensaje = "Hola, $nombreUsuario.\n\nGracias por registrarte en Cronos.\n¡Tu cuenta fue creada con éxito!";
        $this->enviarCorreo($emailDestino, $asunto, $mensaje, 'Registro Usuario');
    }

    // Mail al modificar sus datos
    public function enviarMailModificacion($emailDestino, $nombreUsuario)
    {
        $asunto = 'Datos actualizados correctamente.';
        $mensaje = "Hola, $nombreUsuario.\n\nTe informamos que tus datos personales han sido modificados correctamente en Cronos.";
        $this->enviarCorreo($emailDestino, $asunto, $mensaje, 'Modificación Usuario');
    }

    // Mail al eliminar su cuenta
    public function enviarMailEliminacion($emailDestino, $nombreUsuario)
    {
        $asunto = 'Cuenta eliminada.';
        $mensaje = "Hola, $nombreUsuario.\n\nTu cuenta ha sido eliminada de Cronos. Lamentamos verte partir.\n\nSi fue un error, podés registrarte nuevamente en cualquier momento.";
        $this->enviarCorreo($emailDestino, $asunto, $mensaje, 'Eliminación Usuario');
    }
}
