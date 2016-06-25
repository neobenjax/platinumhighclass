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
            	$header = 'From: hack@platinumhighclass.com';
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
            $whitelist = array('token','nombre','edad','sexo','peso','talla','tel', 'email', 'cirugia');
            
            // Building an array with the $_POST-superglobal 
            foreach ($_POST as $key=>$item) {
                    
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
			$message .= "<tr><td><strong>Edad:</strong> </td><td>" . strip_tags($_POST['edad']) . "</td></tr>";
			$message .= "<tr><td><strong>Sexo:</strong> </td><td>" . strip_tags($_POST['sexo']) . "</td></tr>";
			$message .= "<tr><td><strong>Peso:</strong> </td><td>" . strip_tags($_POST['peso']) . " kg</td></tr>";
			$message .= "<tr><td><strong>Talla:</strong> </td><td>" . strip_tags($_POST['talla']) . " cm</td></tr>";
			$message .= "<tr><td><strong>Telefono:</strong> </td><td>" . strip_tags($_POST['tel']) . "</td></tr>";
			$message .= "<tr><td><strong>E-Mail:</strong> </td><td>" . strip_tags($_POST['email']) . "</td></tr>";
			$message .= "<tr><td><strong>Cirugia:</strong> </td><td>" . strip_tags($_POST['cirugia']) . "</td></tr>";			
			$message .= "</table>";
			$message .= "</body></html>";
	
	//DATOS DE ENVIO
	//   CHANGE THE BELOW VARIABLES TO YOUR NEEDS
             
			//$to = 'neobenjax@gmail.com';
			$to = 'besaze@hotmail.com';
			//$to= 'claudiamilla@prodigy.net.mx';
			
			$subject = 'Prueba - Correo de Contacto';
			
			$headers = "From: " . strip_tags($_POST['email']) . "\r\n";
			$headers .= "Reply-To: ". strip_tags($_POST['email']) . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            if (mail($to, $subject, $message, $headers)) {
              echo '<hr><img src="http://platinumhighclass.com/blasts/enero_2012/membrete_platinum_01.gif" alt="Platinum High Class" /><br>Su Mensaje ha sido enviado<hr>';
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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Contacto - Informes</title>
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
(function($) { $.InFieldLabels = function(label, field, options) { var base = this; base.$label = $(label); base.$field = $(field); base.$label.data("InFieldLabels", base); base.showing = true; base.init = function() { base.options = $.extend({}, $.InFieldLabels.defaultOptions, options); base.$label.css('position', 'absolute'); var fieldPosition = base.$field.position(); base.$label.css({ 'left': fieldPosition.left, 'top': fieldPosition.top }).addClass(base.options.labelClass); if (base.$field.val() != "") { base.$label.hide(); base.showing = false; }; base.$field.focus(function() { base.fadeOnFocus(); }).blur(function() { base.checkForEmpty(true); }).bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }).change(function(e) { base.checkForEmpty(); }).bind('onPropertyChange', function() { base.checkForEmpty(); }); }; base.fadeOnFocus = function() { if (base.showing) { base.setOpacity(base.options.fadeOpacity); }; }; base.setOpacity = function(opacity) { base.$label.stop().animate({ opacity: opacity }, base.options.fadeDuration); base.showing = (opacity > 0.0); }; base.checkForEmpty = function(blur) { if (base.$field.val() == "") { base.prepForShow(); base.setOpacity(blur ? 1.0 : base.options.fadeOpacity); } else { base.setOpacity(0.0); }; }; base.prepForShow = function(e) { if (!base.showing) { base.$label.css({ opacity: 0.0 }).show(); base.$field.bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }); }; }; base.hideOnChange = function(e) { if ((e.keyCode == 16) || (e.keyCode == 9)) return; if (base.showing) { base.$label.hide(); base.showing = false; }; base.$field.unbind('keydown.infieldlabel'); }; base.init(); }; $.InFieldLabels.defaultOptions = { fadeOpacity: 0.5, fadeDuration: 300, labelClass: 'infield' }; $.fn.inFieldLabels = function(options) { return this.each(function() { var for_attr = $(this).attr('for'); if (!for_attr) return; var $field = $("input#" + for_attr + "[type='text']," + "input#" + for_attr + "[type='password']," + "input#" + for_attr + "[type='tel']," + "input#" + for_attr + "[type='email']," + "textarea#" + for_attr); if ($field.length == 0) return; (new $.InFieldLabels(this, $field[0], options)); }); }; })(jQuery);


    $("#ContactoForm label").inFieldLabels();
});

</script>
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
</head>
<?php
   // generate a new token for the $_SESSION superglobal and put them in a hidden field
	$newToken = generateFormToken('form1');   
?>
<body>

<div id="contacto">
 <h2>Contacto - Informes</h2>

 <form id="ContactoForm" action="contacto.php" method="post">
 	<fieldset>
         <p>
         <input type="hidden" name="token" value="<?php echo $newToken; ?>">
            <label for="nombre">Nombre Completo</label>
            <input id="nombre" name="nombre" type="text" class="text" value="" required />
         </p>
         
         <p>
            <label for="edad">Edad</label>
            <input id="edad" name="edad" type="text" class="text" value="" required />
         </p>
         
         <p>
            <select id="sexo" name="sexo" type="text" class="text">
              <option value="Masculino">Masculino</option>
              <option value="Femenino">Femenino</option>
            </select>
         </p>
         <p>
            <label for="peso">Peso</label>
            <input id="peso" name="peso" type="text" class="text" value="" required />
         </p>
         <p>
            <label for="talla">Talla</label>
            <input id="talla" name="talla" type="text" class="text" value="" required />
         </p>
        
         <p>
            <label for="tel">Tel&eacute;fono</label>
            <input id="tel" name="tel" type="tel" class="text" value=""  required />
         </p>
        
         <p>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" class="text" value="" required />
         </p>
         
         <p>           
           <select id="cirugia" name="cirugia" type="text" class="text">
              <option value="Rejuvenecimiento Facial">Rejuvenecimiento Facial</option>
              <option value="Cirugia de Parpados">Cirugia de Parpados</option>
              <option value="Cirugía de Orejas">Cirugía de Orejas</option>
              <option value="Nariz">Nariz</option>
              <option value="Rellenos Faciales">Rellenos Faciales</option>
              <option value="Implantes de Mentón">Implantes de Mentón</option>
              <option value="Adelgazamiento de Mejillas">Adelgazamiento de Mejillas</option>
              <option value="Aumento de Busto">Aumento de Busto</option>
              <option value="Levantamiento y Reafirmación de busto ">Levantamiento y Reafirmación de busto </option>
              <option value="Reducción de Busto">Reducción de Busto</option>
              <option value="Reconstrucción de Busto">Reconstrucción de Busto</option>
              <option value="Lipoescultura">Lipoescultura</option>
              <option value="Cirugía de Abdomen">Cirugía de Abdomen</option>
              <option value="Glúteos">Glúteos</option>
              <option value="Pantorrillas">Pantorrillas</option>
              <option value="Otros">Otros</option>
           </select>
         </p>        
        
         <p>
            <button id="enviarMail" type="submit">Enviar</button>
         </p>
 	</fieldset>

 </form>
</div>
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
(function($) { $.InFieldLabels = function(label, field, options) { var base = this; base.$label = $(label); base.$field = $(field); base.$label.data("InFieldLabels", base); base.showing = true; base.init = function() { base.options = $.extend({}, $.InFieldLabels.defaultOptions, options); base.$label.css('position', 'absolute'); var fieldPosition = base.$field.position(); base.$label.css({ 'left': fieldPosition.left, 'top': fieldPosition.top }).addClass(base.options.labelClass); if (base.$field.val() != "") { base.$label.hide(); base.showing = false; }; base.$field.focus(function() { base.fadeOnFocus(); }).blur(function() { base.checkForEmpty(true); }).bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }).change(function(e) { base.checkForEmpty(); }).bind('onPropertyChange', function() { base.checkForEmpty(); }); }; base.fadeOnFocus = function() { if (base.showing) { base.setOpacity(base.options.fadeOpacity); }; }; base.setOpacity = function(opacity) { base.$label.stop().animate({ opacity: opacity }, base.options.fadeDuration); base.showing = (opacity > 0.0); }; base.checkForEmpty = function(blur) { if (base.$field.val() == "") { base.prepForShow(); base.setOpacity(blur ? 1.0 : base.options.fadeOpacity); } else { base.setOpacity(0.0); }; }; base.prepForShow = function(e) { if (!base.showing) { base.$label.css({ opacity: 0.0 }).show(); base.$field.bind('keydown.infieldlabel', function(e) { base.hideOnChange(e); }); }; }; base.hideOnChange = function(e) { if ((e.keyCode == 16) || (e.keyCode == 9)) return; if (base.showing) { base.$label.hide(); base.showing = false; }; base.$field.unbind('keydown.infieldlabel'); }; base.init(); }; $.InFieldLabels.defaultOptions = { fadeOpacity: 0.5, fadeDuration: 300, labelClass: 'infield' }; $.fn.inFieldLabels = function(options) { return this.each(function() { var for_attr = $(this).attr('for'); if (!for_attr) return; var $field = $("input#" + for_attr + "[type='text']," + "input#" + for_attr + "[type='password']," + "input#" + for_attr + "[type='tel']," + "input#" + for_attr + "[type='email']," + "textarea#" + for_attr); if ($field.length == 0) return; (new $.InFieldLabels(this, $field[0], options)); }); }; })(jQuery);


        							$("#ContactoForm label").inFieldLabels();
								   });

</script>

</body>
</html>
