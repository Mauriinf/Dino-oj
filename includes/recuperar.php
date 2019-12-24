<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'mailer/Exception.php';
	require 'mailer/PHPMailer.php';
	require 'mailer/SMTP.php';
	
	
	// Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);

	try {
	    //Server settings
	    $mail->SMTPDebug = 2;                                       // Enable verbose debug output
	    $mail->isSMTP();                                            // Set mailer to use SMTP
	    $mail->Host       = 'smtp.office365.com';  // Specify main and backup SMTP servers
	    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
	    $mail->Username   = 'Judge_Dino_Online@hotmail.com';                     // SMTP username
	    $mail->Password   = 'juezdinoinformatica2018';                               // SMTP password
	    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
	    $mail->Port       = 587;                                    // TCP port to connect to

	    //Recipients
	    $mail->setFrom('Judge_Dino_Online@hotmail.com', 'YOOOO');
	    $mail->addAddress('mauricio_nina@hotmail.com', 'Mauricioooooooo');     // Add a recipient


	

	    // Content
	    $mail->isHTML(true);                                  // Set email format to HTML
	    $mail->Subject = 'Asunto';
	    $mail->Body    = 'hola este es un coooreo alternativo';

	    $mail->send();
	    echo 'se envio correctamente';
	} catch (Exception $e) {
	    echo "hubo un error al enviar mensaje: {$mail->ErrorInfo}";
	}
		
	
?>

