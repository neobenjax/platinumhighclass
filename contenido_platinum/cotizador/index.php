<?php

    session_start();
    
    function getRealIp() {
       if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
         $ip=$_SERVER['HTTP_CLIENT_IP'];
       } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
         $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
       } else {
         $ip=$_SERVER['REMOTE_ADDR'];
       }
       return $ip;
    }

    function writeLog($where) {
    
    	$ip = getRealIp(); // Get the IP from superglobal
    	$host = gethostbyaddr($ip);    // Try to locate the host of the attack
    	$date = date("d M Y");
    	
    	// create a logging message with php heredoc syntax
    	$logging = <<<LOG
    		\n
    		<< Start of Message >>
    		There was a hacking attempt on your form. \n 
    		Date of Attack: {$date}
    		IP-Adress: {$ip} \n
    		Host of Attacker: {$host}
    		Point of Attack: {$where}
    		<< End of Message >>
LOG;
// Awkward but LOG must be flush left
    
            // open log file
    		if($handle = fopen('hacklog.log', 'a')) {
    		
    			fputs($handle, $logging);  // write the Data to file
    			fclose($handle);           // close the file
    			
    		} else {  // if first method is not working, for example because of wrong file permissions, email the data
    		
    			$to = 'besaze@hotmail.com';  
            	$subject = 'Atentado de Hacking';
            	$header = 'From: ataque@platinumhighclass.com';
            	if (mail($to, $subject, $logging, $header)) {
            		echo "Se ha enviado una notificacion";
            	}
    
    		}
    }
	
    function verifyFormToken($form) {
        
        // check if a session is started and a token is transmitted, if not return an error
    	if(!isset($_SESSION[$form.'_token'])) { 
    		return false;
        }
    	
    	// check if the form is sent with token in it
    	if(!isset($_POST['token'])) {
    		return false;
        }
    	
    	// compare the tokens against each other if they are still the same
    	if ($_SESSION[$form.'_token'] !== $_POST['token']) {
    		return false;
        }
    	
    	return true;
    }
    
    function generateFormToken($form) {
    
        // generate a token from an unique value, took from microtime, you can also use salt-values, other crypting methods...
    	$token = md5(uniqid(microtime(), true));  
    	
    	// Write the generated token to the session variable to check it against the hidden field when the form is sent
    	$_SESSION[$form.'_token'] = $token; 
    	
    	return $token;
    }
	
	// VERIFY LEGITIMACY OF TOKEN
    if (verifyFormToken('form1')) {
    
        // CHECK TO SEE IF THIS IS A MAIL POST
        if (isset($_POST['nombre'])) {
        
            // Building a whitelist array with keys which will send through the form, no others would be accepted later on
            $whitelist = array('token','nombre','fecha','noinvitadas','tel','cel','email', 'ciudad', 'colonia', 'organizadora', 'presupuesto', 'horario', 'duracion', 'serv_animacion', 'serv_gio', 'serv_stripper', 'serv_clases', 'serv_sexologia', 'serv_seduccion', 'serv_vip01', 'serv_vip02', 'serv_vip03', 'serv_burlesque', 'serv_tarot', 'serv_fiestatematica', 'servad_velo', 'servad_kit', 'servad_pastel', 'servad_bocadillos', 'servad_camisetas', 'servad_distintivos', 'servad_inv_impresas', 'servad_inv_virtuales', 'servad_decoracion', 'servad_decoracion_tema', 'servad_globos', 'servad_recuerdos', 'servad_disfraces', 'ab_mesapostres', 'ab_barradulces', 'ab_barracafe', 'ab_galletasdeco', 'ab_paletasbombon', 'ab_cupcakes', 'ab_botellasagua', 'ab_canapes', 'ab_pizzas', 'ab_bocadillosero', 'ab_galletasero', 'ab_pastelesero', 'ab_canapesero', 'mr_juadultos', 'mr_bisuteria', 'mr_bolsas', 'mr_zapatos', 'mr_lenceria', 'mr_todoenuno');
            
            // Building an array with the $_POST-superglobal 
            foreach ($_POST as $key=>$item) {
//                    echo $key . "<br>"; //Chequeo de todas las variables que estan siendo enviadas
                    // Check if the value $key (fieldname from $_POST) can be found in the whitelisting array, if not, die with a short message to the hacker
            		if (!in_array($key, $whitelist)) {
            			
            			writeLog('Campo desconocido');						
            			die("Ataque detectado. Por favor usa solo los campos mostrados");
            			
            		}
            }

			// PREPARE THE BODY OF THE MESSAGE

			$message = '<html><body>';
			$message .= '<img src="http://platinumhighclass.com/blasts/enero_2012/membrete_platinum_01.gif" alt="Platinum High Class" />';
			$message .= '<table rules="all" style="background-color:#f8b6c2; border-color: #f8b6c2;" cellpadding="10">';
			$message .= "<tr style='background: #fff;'><td><strong>Nombre:</strong> </td><td>" . strip_tags($_POST['nombre']) . "</td></tr>";
			$message .= "<tr><td><strong>Fecha Evento:</strong> </td><td>" . strip_tags($_POST['fecha']) . "</td></tr>";
			$message .= "<tr><td><strong>Numero de Invitadas:</strong> </td><td>" . strip_tags($_POST['noinvitadas']) . "</td></tr>";
			$message .= "<tr><td><strong>Telefono:</strong> </td><td>" . strip_tags($_POST['tel']) . " </td></tr>";
			$message .= "<tr><td><strong>Celular:</strong> </td><td>" . strip_tags($_POST['cel']) . " </td></tr>";
			$message .= "<tr><td><strong>Correo Electronico:</strong> </td><td>" . strip_tags($_POST['email']) . "</td></tr>";
			$message .= "<tr><td><strong>Ciudad:</strong> </td><td>" . strip_tags($_POST['ciudad']) . "</td></tr>";
			$message .= "<tr><td><strong>Colonia:</strong> </td><td>" . strip_tags($_POST['colonia']) . "</td></tr>";			
			$message .= "<tr><td><strong>Organizadora:</strong> </td><td>" . strip_tags($_POST['organizadora']) . "</td></tr>";			
			$message .= "<tr><td><strong>Presupuesto:</strong> </td><td>" . strip_tags($_POST['presupuesto']) . "</td></tr>";			
			$message .= "<tr><td><strong>Horario:</strong> </td><td>" . strip_tags($_POST['horario']) . "</td></tr>";			
			$message .= "<tr><td><strong>Duracion:</strong> </td><td>" . strip_tags($_POST['duracion']) . "</td></tr>";
			$message .= "<tr><td><strong>Deseo Cotizacion de:</strong> </td><td>";
			//Comparando valores de las opciones
			if(strip_tags($_POST['serv_animacion'])=='SI'){
				$message .= "<strong>Animacion Profesional</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['serv_gio'])=='SI'){
				$message .= "<strong>GIO</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['serv_stripper'])=='SI'){
				$message .= "<strong>Stripper</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['serv_clases'])=='SI'){
				$message .= "<strong>Clases de Striptease y Pole Dance</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['serv_sexologia'])=='SI'){
				$message .= "<strong>Clases de Sexologia</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['serv_vip01'])=='SI'){
				$message .= "<strong>VIP PLATINUM 2 Horas</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['serv_vip02'])=='SI'){
				$message .= "<strong>VIP PLATINUM 2 Horas treinta</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['serv_vip03'])=='SI'){
				$message .= "<strong>VIP PLATINUM Lujo</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['serv_burlesque'])=='SI'){
				$message .= "<strong>Burlesque Party</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['serv_tarot'])=='SI'){
				$message .= "<strong>Tarot</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['serv_fiestatematica'])=='SI'){
				$message .= "<strong>Fiesta Tematica</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['serv_seduccion'])=='SI'){
				$message .= "<strong>Clases de Seduccion</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['servad_velo'])=='SI'){
				$message .= "<strong>Velo y Banda Novia</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['servad_kit'])=='SI'){
				$message .= "<strong>Kit de Fiesta</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['servad_pastel'])=='SI'){
				$message .= "<strong>Pastel</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['servad_bocadillos'])=='SI'){
				$message .= "<strong>Bocadillos</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['servad_camisetas'])=='SI'){
				$message .= "<strong>Camisetas</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['servad_distintivos'])=='SI'){
				$message .= "<strong>Distintivos</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['servad_inv_impresas'])=='SI'){
				$message .= "<strong>Invitaciones Impresas</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['servad_inv_virtuales'])=='SI'){
				$message .= "<strong>Invitaciones Virtuales</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['servad_decoracion'])=='SI'){
				$message .= "<strong>Decoracion</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['servad_decoracion_tema'])=='SI'){
				$message .= "<strong>Decoracion Tematica</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['servad_globos'])=='SI'){
				$message .= "<strong>Globos</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['servad_recuerdos'])=='SI'){
				$message .= "<strong>Recuerdos</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['servad_disfraces'])=='SI'){
				$message .= "<strong>Disfraces</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['ab_mesapostres'])=='SI'){
				$message .= "<strong>Mesa de Postres</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['ab_barradulces'])=='SI'){
				$message .= "<strong>Barra de Dulces</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['ab_barracafe'])=='SI'){
				$message .= "<strong>Barra de Cafe</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['ab_galletasdeco'])=='SI'){
				$message .= "<strong>Galletas Decoradas</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['ab_paletasbombon'])=='SI'){
				$message .= "<strong>Paletas de Bombon</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['ab_cupcakes'])=='SI'){
				$message .= "<strong>Cupcakes</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['ab_botellasagua'])=='SI'){
				$message .= "<strong>Botellas de Agua</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['ab_canapes'])=='SI'){
				$message .= "<strong>Canapes</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['ab_pizzas'])=='SI'){
				$message .= "<strong>Pizzas Especiales</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['ab_bocadillosero'])=='SI'){
				$message .= "<strong>Bocadillos Eroticos</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['ab_galletasero'])=='SI'){
				$message .= "<strong>Galletas Eroticas</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['ab_pastelesero'])=='SI'){
				$message .= "<strong>Pasteles Eroticos</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['ab_canapesero'])=='SI'){
				$message .= "<strong>Canapes Eroticos</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['mr_juadultos'])=='SI'){
				$message .= "<strong>Mesa de Juguetes para Adultos</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['mr_bisuteria'])=='SI'){
				$message .= "<strong>Mesa de Bisuteria</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['mr_bolsas'])=='SI'){
				$message .= "<strong>Mesa de Bolsas</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['mr_zapatos'])=='SI'){
				$message .= "<strong>Mesa de Zapatos</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['mr_lenceria'])=='SI'){
				$message .= "<strong>Mesa de Lenceria</strong><br>";
			} else {$message .= "";}
			if(strip_tags($_POST['mr_todoenuno'])=='SI'){
				$message .= "<strong>Mesa todo en Uno</strong><br>";
			} else {$message .= "";}
			
			
			$message .= "</td><td>";
			$message .= "</table>";
			$message .= "</body></html>";
	
	//DATOS DE ENVIO
	//   CHANGE THE BELOW VARIABLES TO YOUR NEEDS
             
			//$to = 'neobenjax@gmail.com';
			//$to = 'besaze@hotmail.com';
			$to= 'platinumhighclass@prodigy.net.mx';
			
			$subject = 'Platinum High Class - Cotizador';
			
			$headers = "From: " . strip_tags($_POST['email']) . "\r\n";
			$headers .= "Reply-To: ". strip_tags($_POST['email']) . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            if (mail($to, $subject, $message, $headers)) {
              echo '<hr><img src="http://platinumhighclass.com/blasts/enero_2012/membrete_platinum_01.gif" alt="Platinum High Class" /><br><strong>Su Mensaje ha sido enviado</strong><hr>';
            } else {
              echo 'Ooops!! Ha habido un problema con el envio. Lo sentimos';
            }
            
            // DON'T BOTHER CONTINUING TO THE HTML...
            die();
        
        }
    } else {
    
   		if (!isset($_SESSION[$form.'_token'])) {
   		
   		} else {
   			echo "Ataque detectado. Cuidate!";
   			writeLog('Formtoken');
   	    }
   
   	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cotizador</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<!--CALENDARIO-->
<link rel="stylesheet" href="calendario/jquery.ui.all.css">
<script src="calendario/jquery-1.7.1.js"></script>
<script src="calendario/jquery.ui.core.js"></script>
<script src="calendario/jquery.ui.widget.js"></script>
<script src="calendario/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="calendario/demos.css">
<script>
	$(function() {
		$( "#datepicker" ).datepicker();
	});
</script>
<!--/CALENDARIO-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
<script type="text/javascript">

 $(document).ready(function() {
/*
* In-Field Label jQuery Plugin
* http://fuelyourcoding.com/scripts/infield.html
*
* Copyright (c) 2009 Doug Neiner
* Dual licensed under the MIT and GPL licenses.
* Uses the same license as jQuery, see:
* http://docs.jquery.com/License
*
* @version 0.1
*/
(function($) { $.InFieldLabels = function(label, field, options) { var base = this; base.$label = $(label); base.$field = $(field); base.$label.data("InFieldLabels", base); base.showing = true; base.init = function() { base.options = $.extend({}, $.InFieldLabels.defaultOptions, options); base.$label.css('position', 'absolute'); var fieldPosition = base.$field.position(); base.$label.css({ 'left': fieldPosition.left, 'top': fieldPosition.top }).addClass(base.options.labelClass); if (base.$field.val() != "") { base.$label.hide(); base.showing = false; }; base.$field.focus(function() { base.fadeOnFocus(); }).blur(function() { base.checkForEmpty(true); }).bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }).change(function(e) { base.checkForEmpty(); }).bind('onPropertyChange', function() { base.checkForEmpty(); }); }; base.fadeOnFocus = function() { if (base.showing) { base.setOpacity(base.options.fadeOpacity); }; }; base.setOpacity = function(opacity) { base.$label.stop().animate({ opacity: opacity }, base.options.fadeDuration); base.showing = (opacity > 0.0); }; base.checkForEmpty = function(blur) { if (base.$field.val() == "") { base.prepForShow(); base.setOpacity(blur ? 1.0 : base.options.fadeOpacity); } else { base.setOpacity(0.0); }; }; base.prepForShow = function(e) { if (!base.showing) { base.$label.css({ opacity: 0.0 }).show(); base.$field.bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }); }; }; base.hideOnChange = function(e) { if ((e.keyCode == 16) || (e.keyCode == 9)) return; if (base.showing) { base.$label.hide(); base.showing = false; }; base.$field.unbind('keydown.infieldlabel'); }; base.init(); }; $.InFieldLabels.defaultOptions = { fadeOpacity: 0.5, fadeDuration: 300, labelClass: 'infield' }; $.fn.inFieldLabels = function(options) { return this.each(function() { var for_attr = $(this).attr('for'); if (!for_attr) return; var $field = $("input#" + for_attr + "[type='text']," + "input#" + for_attr + "[type='number']," + "input#" + for_attr + "[type='email']," + "textarea#" + for_attr); if ($field.length == 0) return; (new $.InFieldLabels(this, $field[0], options)); }); }; })(jQuery);


    $("#CotizadorForm label").inFieldLabels();
});

</script>




</head>
<?php
   // generate a new token for the $_SESSION superglobal and put them in a hidden field
	$newToken = generateFormToken('form1');   
?>
<body>
<div id="contenido">
	<div id="logo">
   	  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="400" height="248" id="FlashID" title="logo">
    	  <param name="movie" value="cotizador_logo.swf" />
    	  <param name="quality" value="high" />
    	  <param name="wmode" value="transparent" />
    	  <param name="swfversion" value="8.0.35.0" />
    	  <!-- Esta etiqueta param indica a los usuarios de Flash Player 6.0 r65 o posterior que descarguen la versión más reciente de Flash Player. Elimínela si no desea que los usuarios vean el mensaje. -->
    	  <param name="expressinstall" value="Scripts/expressInstall.swf" />
    	  <!-- La siguiente etiqueta object es para navegadores distintos de IE. Ocúltela a IE mediante IECC. -->
    	  <!--[if !IE]>-->
    	  <object type="application/x-shockwave-flash" data="cotizador_logo.swf" width="400" height="248">
    	    <!--<![endif]-->
    	    <param name="quality" value="high" />
    	    <param name="wmode" value="transparent" />
    	    <param name="swfversion" value="8.0.35.0" />
    	    <param name="expressinstall" value="Scripts/expressInstall.swf" />
    	    <!-- El navegador muestra el siguiente contenido alternativo para usuarios con Flash Player 6.0 o versiones anteriores. -->
    	    <div>
    	      <h4>El contenido de esta página requiere una versión más reciente de Adobe Flash Player.</h4>
    	      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obtener Adobe Flash Player" width="112" height="33" /></a></p>
  	      </div>
    	    <!--[if !IE]>-->
  	    </object>
    	  <!--<![endif]-->
  	  </object>
	</div>
    <div id="logo_servicios">
   	  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="400" height="248" id="FlashID2" title="Servicios">
    	  <param name="movie" value="cotizador_logo_servicios.swf" />
    	  <param name="quality" value="high" />
    	  <param name="wmode" value="transparent" />
    	  <param name="swfversion" value="8.0.35.0" />
    	  <!-- Esta etiqueta param indica a los usuarios de Flash Player 6.0 r65 o posterior que descarguen la versión más reciente de Flash Player. Elimínela si no desea que los usuarios vean el mensaje. -->
    	  <param name="expressinstall" value="Scripts/expressInstall.swf" />
    	  <!-- La siguiente etiqueta object es para navegadores distintos de IE. Ocúltela a IE mediante IECC. -->
    	  <!--[if !IE]>-->
    	  <object type="application/x-shockwave-flash" data="cotizador_logo_servicios.swf" width="400" height="248">
    	    <!--<![endif]-->
    	    <param name="quality" value="high" />
    	    <param name="wmode" value="transparent" />
    	    <param name="swfversion" value="8.0.35.0" />
    	    <param name="expressinstall" value="Scripts/expressInstall.swf" />
    	    <!-- El navegador muestra el siguiente contenido alternativo para usuarios con Flash Player 6.0 o versiones anteriores. -->
    	    <div>
    	      <h4>El contenido de esta página requiere una versión más reciente de Adobe Flash Player.</h4>
    	      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obtener Adobe Flash Player" width="112" height="33" /></a></p>
  	      </div>
    	    <!--[if !IE]>-->
  	    </object>
    	  <!--<![endif]-->
  	  </object>
    </div>
    <div id="logo_servicios_adicionales">
   	  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="400" height="248" id="FlashID3" title="servicios adicionales">
    	  <param name="movie" value="cotizador_logo_serv_adicionales.swf" />
    	  <param name="quality" value="high" />
    	  <param name="wmode" value="transparent" />
    	  <param name="swfversion" value="8.0.35.0" />
    	  <!-- Esta etiqueta param indica a los usuarios de Flash Player 6.0 r65 o posterior que descarguen la versión más reciente de Flash Player. Elimínela si no desea que los usuarios vean el mensaje. -->
    	  <param name="expressinstall" value="Scripts/expressInstall.swf" />
    	  <!-- La siguiente etiqueta object es para navegadores distintos de IE. Ocúltela a IE mediante IECC. -->
    	  <!--[if !IE]>-->
    	  <object type="application/x-shockwave-flash" data="cotizador_logo_serv_adicionales.swf" width="400" height="248">
    	    <!--<![endif]-->
    	    <param name="quality" value="high" />
    	    <param name="wmode" value="transparent" />
    	    <param name="swfversion" value="8.0.35.0" />
    	    <param name="expressinstall" value="Scripts/expressInstall.swf" />
    	    <!-- El navegador muestra el siguiente contenido alternativo para usuarios con Flash Player 6.0 o versiones anteriores. -->
    	    <div>
    	      <h4>El contenido de esta página requiere una versión más reciente de Adobe Flash Player.</h4>
    	      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obtener Adobe Flash Player" width="112" height="33" /></a></p>
  	      </div>
    	    <!--[if !IE]>-->
  	    </object>
    	  <!--<![endif]-->
  	  </object>
    </div>
    <div id="logo_alimentosbebidas">
   	  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="400" height="248" id="FlashID4" title="Alimentos y Bebidas">
    	  <param name="movie" value="cotizador_logo_alimentosbebidas.swf" />
    	  <param name="quality" value="high" />
    	  <param name="wmode" value="transparent" />
    	  <param name="swfversion" value="8.0.35.0" />
    	  <!-- Esta etiqueta param indica a los usuarios de Flash Player 6.0 r65 o posterior que descarguen la versión más reciente de Flash Player. Elimínela si no desea que los usuarios vean el mensaje. -->
    	  <param name="expressinstall" value="Scripts/expressInstall.swf" />
    	  <!-- La siguiente etiqueta object es para navegadores distintos de IE. Ocúltela a IE mediante IECC. -->
    	  <!--[if !IE]>-->
    	  <object type="application/x-shockwave-flash" data="cotizador_logo_alimentosbebidas.swf" width="400" height="248">
    	    <!--<![endif]-->
    	    <param name="quality" value="high" />
    	    <param name="wmode" value="transparent" />
    	    <param name="swfversion" value="8.0.35.0" />
    	    <param name="expressinstall" value="Scripts/expressInstall.swf" />
    	    <!-- El navegador muestra el siguiente contenido alternativo para usuarios con Flash Player 6.0 o versiones anteriores. -->
    	    <div>
    	      <h4>El contenido de esta página requiere una versión más reciente de Adobe Flash Player.</h4>
    	      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obtener Adobe Flash Player" width="112" height="33" /></a></p>
  	      </div>
    	    <!--[if !IE]>-->
  	    </object>
    	  <!--<![endif]-->
  	  </object>
    </div>
    <div id="logo_mesaregalos">
      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="400" height="248" id="FlashID5" title="Mesa de Regalos">
        <param name="movie" value="cotizador_logo_mesaregalos.swf" />
        <param name="quality" value="high" />
        <param name="wmode" value="transparent" />
        <param name="swfversion" value="8.0.35.0" />
        <!-- Esta etiqueta param indica a los usuarios de Flash Player 6.0 r65 o posterior que descarguen la versión más reciente de Flash Player. Elimínela si no desea que los usuarios vean el mensaje. -->
        <param name="expressinstall" value="Scripts/expressInstall.swf" />
        <!-- La siguiente etiqueta object es para navegadores distintos de IE. Ocúltela a IE mediante IECC. -->
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="cotizador_logo_mesaregalos.swf" width="400" height="248">
          <!--<![endif]-->
          <param name="quality" value="high" />
          <param name="wmode" value="transparent" />
          <param name="swfversion" value="8.0.35.0" />
          <param name="expressinstall" value="Scripts/expressInstall.swf" />
          <!-- El navegador muestra el siguiente contenido alternativo para usuarios con Flash Player 6.0 o versiones anteriores. -->
          <div>
            <h4>El contenido de esta página requiere una versión más reciente de Adobe Flash Player.</h4>
            <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obtener Adobe Flash Player" width="112" height="33" /></a></p>
          </div>
          <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
      </object>
    </div>
    <div id="corazon">
   	  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="300" height="278" id="FlashID6" title="Contrata">
    	  <param name="movie" value="cotizador_corazon.swf" />
    	  <param name="quality" value="high" />
    	  <param name="wmode" value="transparent" />
    	  <param name="swfversion" value="8.0.35.0" />
    	  <!-- Esta etiqueta param indica a los usuarios de Flash Player 6.0 r65 o posterior que descarguen la versión más reciente de Flash Player. Elimínela si no desea que los usuarios vean el mensaje. -->
    	  <param name="expressinstall" value="Scripts/expressInstall.swf" />
    	  <!-- La siguiente etiqueta object es para navegadores distintos de IE. Ocúltela a IE mediante IECC. -->
    	  <!--[if !IE]>-->
    	  <object type="application/x-shockwave-flash" data="cotizador_corazon.swf" width="300" height="278">
    	    <!--<![endif]-->
    	    <param name="quality" value="high" />
    	    <param name="wmode" value="transparent" />
    	    <param name="swfversion" value="8.0.35.0" />
    	    <param name="expressinstall" value="Scripts/expressInstall.swf" />
    	    <!-- El navegador muestra el siguiente contenido alternativo para usuarios con Flash Player 6.0 o versiones anteriores. -->
    	    <div>
    	      <h4>El contenido de esta página requiere una versión más reciente de Adobe Flash Player.</h4>
    	      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obtener Adobe Flash Player" width="112" height="33" /></a></p>
  	      </div>
    	    <!--[if !IE]>-->
  	    </object>
    	  <!--<![endif]-->
  	  </object>
    </div>
    
<form action="index.php" method="post" id="CotizadorForm">
<fieldset>
<input type="hidden" name="token" value="<?php echo $newToken; ?>">
    	<img src="images/lbl_nombre.png" class="lbl_nombre"/>
<input name="nombre" type="text" class="box_nombre" required="required"  />
        
        <img src="images/lbl_fecha.png" class="lbl_fecha"/>
        <input name="fecha" id="datepicker" type="text" class="box_fecha" required="required"  />
        
        <img src="images/lbl_noinvitadas.png" class="lbl_noinvitadas"/>
        <input name="noinvitadas" type="number" class="box_noinvitadas" required="required"  />
        
        <img src="images/lbl_tel.png" class="lbl_tel"/>
        <input name="tel" type="text" class="box_tel" required="required"  />
        
        <img src="images/lbl_cel.png" class="lbl_cel"/>
        <input name="cel" type="text" class="box_cel" required="required"  />
        
        <img src="images/lbl_email.png" class="lbl_email"/>
        <input name="email" type="email" class="box_email" required="required"  />
        
        <img src="images/lbl_ciudad.png" class="lbl_ciudad"/>
        <input name="ciudad" type="text" class="box_ciudad" required="required"  />
        
        <img src="images/lbl_colonia.png" class="lbl_colonia"/>
        <input name="colonia" type="text" class="box_colonia" required="required"  />
        
        <img src="images/lbl_organizadora.png" class="lbl_organizadora"/>
        <input name="organizadora" type="text" class="box_organizadora" required="required"  />
        
        <img src="images/lbl_presupuesto.png" class="lbl_presupuesto"/>
        <input name="presupuesto" type="text" class="box_presupuesto" required="required"  />
        
        <img src="images/lbl_horario.png" class="lbl_horario"/>
        <input name="horario" type="text" class="box_horario" required="required"  />
        
        <img src="images/lbl_duracion.png" class="lbl_duracion"/>
        <input name="duracion" type="text" class="box_duracion" required="required"  />

        <img src="images/servicios/lbl_animacion.png" class="lbl_animacion"/>
        <select name="serv_animacion" class="box_animacion">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios/lbl_gio.png" class="lbl_gio"/>
        <select name="serv_gio" class="box_gio">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios/lbl_stripper.png" class="lbl_stripper"/>
        <select name="serv_stripper" class="box_stripper">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios/lbl_clases.png" class="lbl_clases"/>
        <select name="serv_clases" class="box_clases">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios/lbl_sexologia.png" class="lbl_sexologia"/>
        <select name="serv_sexologia" class="box_sexologia">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios/lbl_seduccion.png" class="lbl_seduccion"/>
        <select name="serv_seduccion" class="box_seduccion">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios/lbl_vip01.png" class="lbl_vip01"/>
        <select name="serv_vip01" class="box_vip01">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios/lbl_vip02.png" class="lbl_vip02"/>
        <select name="serv_vip02" class="box_vip02">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios/lbl_vip03.png" class="lbl_vip03"/>
        <select name="serv_vip03" class="box_vip03">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios/lbl_burlesque.png" class="lbl_burlesque"/>
        <select name="serv_burlesque" class="box_burlesque">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios/lbl_tarot.png" class="lbl_tarot"/>
        <select name="serv_tarot" class="box_tarot">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios/lbl_fiestatematica.png" class="lbl_fiestatematica"/>
        <select name="serv_fiestatematica" class="box_fiestatematica">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios_adicionales/01_velo.png" class="lbl_velo"/>
<select name="servad_velo" class="box_velo">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios_adicionales/02_kit.png" class="lbl_kitfiesta"/>
        <select name="servad_kit" class="box_kitfiesta">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios_adicionales/03_pastel.png" class="lbl_pastel"/>
        <select name="servad_pastel" class="box_pastel">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios_adicionales/04_bocadillos.png" class="lbl_bocadillos"/>
        <select name="servad_bocadillos" class="box_bocadillos">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios_adicionales/05_camisetas.png" class="lbl_camisetas"/>
        <select name="servad_camisetas" class="box_camisetas">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios_adicionales/06_distintivos.png" class="lbl_distintivos"/>
        <select name="servad_distintivos" class="box_distintivos">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios_adicionales/07_inv_impresas.png" class="lbl_inv_impresas"/>
        <select name="servad_inv_impresas" class="box_inv_impresas">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
                
        <img src="images/servicios_adicionales/08_inv_virtuales.png" class="lbl_inv_virtuales"/>
        <select name="servad_inv_virtuales" class="box_inv_virtuales">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios_adicionales/09_decoracion.png" class="lbl_decoracion"/>
        <select name="servad_decoracion" class="box_decoracion">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios_adicionales/10_decoracion_tema.png" class="lbl_decoracion_tema"/>
        <select name="servad_decoracion_tema" class="box_decoracion_tema">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios_adicionales/11_globos.png" class="lbl_globos"/>
        <select name="servad_globos" class="box_globos">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios_adicionales/12_recuerdos.png" class="lbl_recuerdos"/>
        <select name="servad_recuerdos" class="box_recuerdos">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/servicios_adicionales/13_disfraces.png" class="lbl_disfraces"/>
        <select name="servad_disfraces" class="box_disfraces">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/alimentos_bebidas/01_mesapostres.png" class="lbl_mesapostres"/>
        <select name="ab_mesapostres" class="box_mesapostres">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/alimentos_bebidas/02_barradulces.png" class="lbl_barradulces"/>
        <select name="ab_barradulces" class="box_barradulces">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/alimentos_bebidas/03_barracafe.png" class="lbl_barracafe"/>
        <select name="ab_barracafe" class="box_barracafe">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/alimentos_bebidas/04_galletasdeco.png" class="lbl_galletasdeco"/>
        <select name="ab_galletasdeco" class="box_galletasdeco">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/alimentos_bebidas/05_paletasbombon.png" class="lbl_paletasbombon"/>
        <select name="ab_paletasbombon" class="box_paletasbombon">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/alimentos_bebidas/06_cupcakes.png" class="lbl_cupcakes"/>
        <select name="ab_cupcakes" class="box_cupcakes">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/alimentos_bebidas/07_botellasagua.png" class="lbl_botellasagua"/>
        <select name="ab_botellasagua" class="box_botellasagua">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/alimentos_bebidas/08_canapes.png" class="lbl_canapes"/>
        <select name="ab_canapes" class="box_canapes">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/alimentos_bebidas/09_pizzas.png" class="lbl_pizzas"/>
        <select name="ab_pizzas" class="box_pizzas">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/alimentos_bebidas/10_bocadilloseroticos.png" class="lbl_bocadillosero"/>
        <select name="ab_bocadillosero" class="box_bocadillosero">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/alimentos_bebidas/11_galletaseroticas.png" class="lbl_galletasero"/>
        <select name="ab_galletasero" class="box_galletasero">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/alimentos_bebidas/12_pasteleseroticos.png" class="lbl_pastelesero"/>
        <select name="ab_pastelesero" class="box_pastelesero">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/alimentos_bebidas/13_canapeseroticos.png" class="lbl_canapesero"/>
        <select name="ab_canapesero" class="box_canapesero">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/mesa_regalos/01_juguetesadultos.png" class="lbl_juadultos"/>
        <select name="mr_juadultos" class="box_juadultos">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/mesa_regalos/02_bisuteria.png" class="lbl_bisuteria"/>
        <select name="mr_bisuteria" class="box_bisuteria">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/mesa_regalos/03_bolsas.png" class="lbl_bolsas"/>
        <select name="mr_bolsas" class="box_bolsas">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/mesa_regalos/04_zapatos.png" class="lbl_zapatos"/>
        <select name="mr_zapatos" class="box_zapatos">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/mesa_regalos/05_lenceria.png" class="lbl_lenceria"/>
        <select name="mr_lenceria" class="box_lenceria">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        
        <img src="images/mesa_regalos/06_todoen1.png" class="lbl_todoenuno"/>
        <select name="mr_todoenuno" class="box_todoenuno">
          <option value="NO">NO</option>
          <option value="SI">SI</option>
      	</select>
        <input type="submit" value="" class="enviar"/>
        

    	</fieldset>
  </form>
</div>

<script type="text/javascript">
<!--
swfobject.registerObject("FlashID");
swfobject.registerObject("FlashID2");
swfobject.registerObject("FlashID3");
swfobject.registerObject("FlashID4");
swfobject.registerObject("FlashID5");
swfobject.registerObject("FlashID6");
//-->
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>

<script type="text/javascript">

        $(document).ready(function() {
/*
* In-Field Label jQuery Plugin
* http://fuelyourcoding.com/scripts/infield.html
*
* Copyright (c) 2009 Doug Neiner
* Dual licensed under the MIT and GPL licenses.
* Uses the same license as jQuery, see:
* http://docs.jquery.com/License
*
* @version 0.1
*/
(function($) { $.InFieldLabels = function(label, field, options) { var base = this; base.$label = $(label); base.$field = $(field); base.$label.data("InFieldLabels", base); base.showing = true; base.init = function() { base.options = $.extend({}, $.InFieldLabels.defaultOptions, options); base.$label.css('position', 'absolute'); var fieldPosition = base.$field.position(); base.$label.css({ 'left': fieldPosition.left, 'top': fieldPosition.top }).addClass(base.options.labelClass); if (base.$field.val() != "") { base.$label.hide(); base.showing = false; }; base.$field.focus(function() { base.fadeOnFocus(); }).blur(function() { base.checkForEmpty(true); }).bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }).change(function(e) { base.checkForEmpty(); }).bind('onPropertyChange', function() { base.checkForEmpty(); }); }; base.fadeOnFocus = function() { if (base.showing) { base.setOpacity(base.options.fadeOpacity); }; }; base.setOpacity = function(opacity) { base.$label.stop().animate({ opacity: opacity }, base.options.fadeDuration); base.showing = (opacity > 0.0); }; base.checkForEmpty = function(blur) { if (base.$field.val() == "") { base.prepForShow(); base.setOpacity(blur ? 1.0 : base.options.fadeOpacity); } else { base.setOpacity(0.0); }; }; base.prepForShow = function(e) { if (!base.showing) { base.$label.css({ opacity: 0.0 }).show(); base.$field.bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }); }; }; base.hideOnChange = function(e) { if ((e.keyCode == 16) || (e.keyCode == 9)) return; if (base.showing) { base.$label.hide(); base.showing = false; }; base.$field.unbind('keydown.infieldlabel'); }; base.init(); }; $.InFieldLabels.defaultOptions = { fadeOpacity: 0.5, fadeDuration: 300, labelClass: 'infield' }; $.fn.inFieldLabels = function(options) { return this.each(function() { var for_attr = $(this).attr('for'); if (!for_attr) return; var $field = $("input#" + for_attr + "[type='text']," + "input#" + for_attr + "[type='number']," + "input#" + for_attr + "[type='email']," + "textarea#" + for_attr); if ($field.length == 0) return; (new $.InFieldLabels(this, $field[0], options)); }); }; })(jQuery);


        							$("#CotizadorForm label").inFieldLabels();
								   });

</script>

</body>
</html>
