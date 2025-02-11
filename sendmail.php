<?php

header( "refresh:2;url=index.html#contact" );

/**
 * PHPMailer simple contact form example.
 * If you want to accept and send uploads in your form, look at the send_file_upload example.
 */

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';

$err = false;
$msg = '';
$email = '';

//Apply some basic validation and filtering to the query
if (array_key_exists('message', $_POST)) {
    //Limit length and strip HTML tags
    $message = substr(strip_tags($_POST['message']), 0, 16384);
} else {
    $message = '';
    $msg = 'No message provided!';
    $err = true;
}
//Apply some basic validation and filtering to the name
if (array_key_exists('name', $_POST)) {
    //Limit length and strip HTML tags
    $name = substr(strip_tags($_POST['name']), 0, 255);
} else {
    $name = '';
}

$to = 'schrepf@gmail.com';

//Make sure the address they provided is valid before trying to use it
if (array_key_exists('email', $_POST) && PHPMailer::validateAddress($_POST['email'])) {
    $email = $_POST['email'];
} else {
    $msg .= 'Error: invalid email address provided';
    $err = true;
}


if (!$err) {
    $mail = new PHPMailer();
    // $mail->SMTPDebug  = 2;
    // $mail->isSMTP();
    // $mail->Host = 'localhost';
    // $mail->Port = 465;
    // $mail->SMTPAuth = true;
    // $mail->Username = 'info@villa-frankenwald.de';
    // $mail->Password = '';
    // $mail->SMTPSecure = 'ssl';
    $mail->CharSet = PHPMailer::CHARSET_UTF8;
    //It's important not to use the submitter's address as the from address as it's forgery,
    //which will cause your messages to fail SPF checks.
    //Use an address in your own domain as the from address, put the submitter's address in a reply-to
    $mail->setFrom('info@villa-frankenwald.de', (empty($name) ? 'Contact form' : $name));
    $mail->addAddress($to);
    $mail->addReplyTo($email, $name);
    $mail->Subject = 'Contact form: villa-frankenwald.de';
    $mail->Body = "Contact form submission\n\n" . $message;
    if (!$mail->send()) {
        $msg .= 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        $msg .= 'Message sent!';
    }

}

echo $msg;
 ?>
