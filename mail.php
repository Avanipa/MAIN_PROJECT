<?php 
 
// Import PHPMailer classes into the global namespace 
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
 
require 'assets/mail/src/Exception.php'; 
require 'assets/mail/src/PHPMailer.php'; 
require 'assets/mail/src/SMTP.php'; 
 
function send_mail($recipient, $subject, $message){

    $mail = new PHPMailer; 
 
    $mail->isSMTP();                      // Set mailer to use SMTP 
    $mail->Host = 'smtp.gmail.com';       // Specify main and backup SMTP servers 
    $mail->SMTPAuth = true;               // Enable SMTP authentication 
    $mail->Username = 'employment.exchangee@gmail.com';   // SMTP username 
    $mail->Password = 'hllhymwdhimoveve';   // SMTP password 
    $mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted 
    $mail->Port = 587;                    // TCP port to connect to 
    
    // Sender info 
    $mail->setFrom('employment.exchangee@gmail.com', 'Emplyment Exchange'); 
    // $mail->addReplyTo('reply@codexworld.com', 'CodexWorld'); 
    
    // Add a recipient 
    $mail->addAddress($recipient); 
    
    //$mail->addCC('cc@example.com'); 
    //$mail->addBCC('bcc@example.com'); 
    
    // Set email format to HTML 
    $mail->isHTML(true); 
    
    // Mail subject 
    $mail->Subject = $subject; 
    
    // Mail body content 
    $bodyContent = $message;
    $mail->Body    = $bodyContent; 
    
    // Send email 
    if(!$mail->send()) { 
        echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
    } 
}


?>