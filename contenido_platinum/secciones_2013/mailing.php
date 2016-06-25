<?php
extract($_REQUEST);
	if(!isset($nombre) || $nombre==''){
		echo '<center><h1>Debe ingresar por lo menos "Nombre"/"Email"/"Celular"</h1><br>';
		echo '<h2><a href="javascript:window.close();">Reintentar</a></h2></center>';
		exit;
	} else {
		//Área para confirmacion por correo electrónico
	///////////////////////////////////////////
	// Error reporting:
	error_reporting(E_ALL^E_NOTICE);
	
	// This is the URL your users are redirected,
	// when registered succesfully:
	
	//$redirectURL = 'success.htm';
	
	// Email Function
	
	//$emailAddress = 'besaze@hotmail.com';
	$emailAddress = 'platinumhighclass@prodigy.net.mx';
	// Using session to prevent flooding:
	session_name('quickFeedback');
	session_start();
	
	$_SESSION['lastSubmit'] = time();
	$_SESSION['submitsLastHour'][date('d-m-Y-H')]++;
	
	
	require "phpmailer/class.phpmailer.php";
	
	
	
	
	
		//MAILING
		// Using the PHPMailer class
	
		$mail = new PHPMailer();
		$mail->IsMail();
		
		// Adding the receiving email address
		$mail->AddAddress($emailAddress);
		
		$mail->Subject = 'Correo Proveniente de Contacto en Platinum High Class';
		
		//Armando contenido del mensaje
		$mensaje='';
		$mensaje.='<strong>Corre proveniente de Contacto en Platinum High Class</strong><br>';
		$mensaje.='<strong>Datos del cliente</strong><br>';
		$mensaje.='<strong>Nombre:</strong>'.$nombre.'<br>';
		$mensaje.='<strong>E-Mail:</strong>'.$email.'<br>';
		$mensaje.='<strong>Telefono:</strong>'.$telefono.'<br>';
		$mensaje.='<strong>Celular:</strong>'.$celular.'<br>';
		$mensaje.='<strong>Productos:</strong>'.$productos.'<br>';
		$mensaje.='<strong>Servicios:</strong>'.$servicios.'<br>';
		$mensaje.='<strong>Comentarios:</strong>'.$comentarios.'<br>';
		
		
		$mail->MsgHTML($mensaje);
		
		$mail->AddReplyTo('noreply@'.$_SERVER['HTTP_HOST'], 'Contacto Platinum High Class');
		$mail->SetFrom('noreply@'.$_SERVER['HTTP_HOST'], 'Contacto Platinum High Class');
		
		$mail->Send();
		echo '<center><h1>Se ha enviado un correo a nuestros contactos; en breve se comunicar&aacute;n con usted.</h1><br>';
		echo '<h2>!Buen D&iacute;a!</h2>';
		echo '<h2><a href="javascript:window.close();">Cerrar</a></h2></center></center>';
	}
?>