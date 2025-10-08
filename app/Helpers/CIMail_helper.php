<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (! function_exists('sendEmail')) {
    function sendEmail($emailConfig)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = getenv('EMAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = getenv('EMAIL_USERNAME'); // Gmail của bạn
            $mail->Password   = getenv('EMAIL_PASSWORD');   // App password, không phải password Gmail thường
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = getenv('EMAIL_PORT');

            $mail->setFrom($emailConfig['mail_from_email'], $emailConfig['mail_from_name']);
            $mail->addAddress($emailConfig['mail_to_email'], $emailConfig['mail_to_name']);

            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $emailConfig['mail_subject'];
            $mail->Body    = $emailConfig['mail_body'];

            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            if ($mail->send()) {
                return true;
            } else {
                return false;
            }


            //Server settings
            // $mail->SMTPDebug = 1;                      //Enable verbose debug output
            // $mail->isSMTP();                                            //Send using SMTP
            // $mail->Host       = getenv('EMAIL_HOST');                     //Set the SMTP server to send through
            // $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            // $mail->Username   = getenv('EMAIL_USERNAME');                     //SMTP username
            // $mail->Password   = getenv('EMAIL_PASSWORD');                               //SMTP password
            // $mail->SMTPSecure = getenv('EMAIL_CRYPTO');            //Enable implicit TLS encryption
            // $mail->Port       = getenv('EMAIL_PORT');                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            // //Recipients
            // $mail->setFrom($emailConfig['mail_from_email'], $emailConfig['mail_from_name']);
            // $mail->addAddress($emailConfig['mail_to_email'], $emailConfig['mail_to_name']);     //Add a recipient
            // // $mail->setFrom('from@example.com', 'Mailer');
            // // $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
            // // $mail->addAddress('ellen@example.com');               //Name is optional
            // // $mail->addReplyTo('info@example.com', 'Information');
            // // $mail->addCC('cc@example.com');
            // // $mail->addBCC('bcc@example.com');

            // //Attachments
            // // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            // //Content
            // $mail->isHTML(true);                                  //Set email format to HTML
            // $mail->Subject = $emailConfig['mail_subject'];
            // $mail->Body    = $emailConfig['mail_body'];
            // // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            // // $mail->send();
            // if ($mail->send()) {
            //     return true;
            // } else {
            //     return false;
            // }
            // echo 'Message has been sent';
        } catch (Exception $e) {
            // dd($e->getMessage());
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }
}
