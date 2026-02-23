<?php
// Script de prueba de envío de correo con PHPMailer y la configuración actual

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Configuración desde config.php
define('EMAIL_HOST', 'mail.mangoapp.co');
define('EMAIL_PORT', 587);
define('EMAIL_SECURE', 'tls');
define('EMAIL_NOTIFICACIONES', 'notificaciones@mangoapp.co');

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['destinatario'])) {
    $destinatario = trim($_POST['destinatario']);
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = EMAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL_NOTIFICACIONES;
        $mail->Password = '@Popopo2026'; // Nueva contraseña actualizada
        $mail->SMTPSecure = EMAIL_SECURE;
        $mail->Port = EMAIL_PORT;

        $mail->setFrom(EMAIL_NOTIFICACIONES, 'Prueba ManGo!');
        $mail->addAddress($destinatario);
        $mail->addReplyTo(EMAIL_NOTIFICACIONES, 'Prueba ManGo!');

        $mail->isHTML(true);
        $mail->Subject = 'Prueba de envío de correo ManGo!';
        $mail->Body    = '<h3>¡Este es un correo de prueba enviado desde tu servidor!</h3>';
        $mail->CharSet = 'UTF-8';

        $mail->send();
        $mensaje = 'Correo de prueba enviado correctamente a ' . htmlspecialchars($destinatario);
    } catch (Exception $e) {
        $mensaje = 'Error al enviar el correo: ' . $mail->ErrorInfo;
    }
}
?>
<form method="post" style="margin:2em auto;max-width:400px;padding:2em;border:1px solid #ccc;background:#fafafa;">
    <label for="destinatario">Correo destinatario:</label><br>
    <input type="email" id="destinatario" name="destinatario" value="dannyws@gmail.com" required style="width:100%;padding:0.5em;margin:1em 0;">
    <button type="submit" style="padding:0.5em 1.5em;">Enviar correo de prueba</button>
    <?php if ($mensaje) { echo '<div style="margin-top:1em;color:green;">' . $mensaje . '</div>'; } ?>
</form>
