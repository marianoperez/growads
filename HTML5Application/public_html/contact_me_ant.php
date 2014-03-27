<?php
require_once("inc/class.phpmailer.php");
require_once("inc/class.pop3.php");
require_once("inc/class.smtp.php");
$sentMail = new PHPMailer();
if ($_POST) {
    //check if its an ajax request, exit if not
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        die();
    }

    $to_Email = "info@growads.com.ar"; //Replace with recipient email address
    $subject = 'Contacto Web'; //Subject line for emails
    //check $_POST vars are set, exit if any missing
    if (!isset($_POST["userName"]) || !isset($_POST["userEmail"]) || !isset($_POST["userPhone"]) || !isset($_POST["userMessage"])) {
        die();
    }

    //Sanitize input data using PHP filter_var().
    $user_Name = filter_var($_POST["userName"], FILTER_SANITIZE_STRING);
    $user_Email = filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL);
    $user_Phone = filter_var($_POST["userPhone"], FILTER_SANITIZE_STRING);
    $user_Message = filter_var($_POST["userMessage"], FILTER_SANITIZE_STRING);

    //additional php validation
    if (strlen($user_Name) < 2) { // If length is less than 4 it will throw an HTTP error.
        header('HTTP/1.1 500 Ingrese su Nombre');
        exit();
    }
    if (!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) { //email validation
        header('HTTP/1.1 500 Ingrese un email valido');
        exit();
    }
    if (strlen($user_Phone) < 1) { //check entered data is numbers
        header('HTTP/1.1 500 Ingrese un telefono');
        exit();
    }


    //proceed with PHP email.
    $headers = 'From: ' . $user_Email . '' . "rn" .
            'Reply-To: ' . $user_Email . '' . "rn" .
            'X-Mailer: PHP/' . phpversion();

    $sentMail = mail($to_Email, $subject, $user_Message . '  -' . $user_Name, $headers);

    if (!$sentMail) {
        header('HTTP/1.1 500 No se pudo enviar el mensaje, por favor reintente');
        exit();
    } else {
        echo 'Hola ' . $user_Name . ', Gracias por su Contacto! ';
        echo 'En breve nos pondremos en contacto con usted para asistirlo.';
        echo 'El Equipo de GrowAds.';
    }
}
?>