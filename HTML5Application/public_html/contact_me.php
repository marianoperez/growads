<?php

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
    $user_Name = $_POST['userName'];
    $user_Email = $_POST['userEmail'];
    $user_Phone = $_POST['userPhone'];
    $user_Message = $_POST['userMessage'];

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
    $headers = 'De: '.$user_Email."\r\n";
    $headers .= 'Nombre '.$user_Name.' - Mail '.$user_Email.' - Telefono: '.$user_Phone."\r\n";



    $sentMail = mail("info@growads.com.ar", "Contacto Web", $user_Message, $headers);
    
    if (!$sentMail) {
        header('HTTP/1.1 500 No se pudo enviar el mensaje, por favor reintente');
        exit();
    } else {
        echo 'Hola ' . $user_Name . ', Gracias por su Contacto! ';
        echo 'En breve nos pondremos en contacto con usted.';
        echo 'El Equipo de GrowAds.';
    }
}
?>