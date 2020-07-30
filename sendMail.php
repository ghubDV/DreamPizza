<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

if(empty($_POST)){
  header('location:index?_rdr');
  die();
}

function sendMail($to,$subject,$HTMLMailBody,$nonHTMLMailBody)
{
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'your.smtp.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'your_email_adress';                     // SMTP username
        $mail->Password   = 'your_email_pass';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 25;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('mail_from_email', 'displayed_name');
        $mail->addAddress($to);     // Add a recipient
        /*
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');*/

        // Attachments
        /*
        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');*/    // Optional name

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $HTMLMailBody;
        $mail->AltBody = $nonHTMLMailBody;

        $mail->send();
        return 'sent';
    } catch (Exception $e) {
        return 'fail';
    }
}
