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
            	$header = 'From: cei@cirugiaesteticaintegral.com';
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
			$message .= '<img src="http://cirugiaesteticaintegral.com/images/logotry_w.png" alt="Cirugia Estetica Integral" />';
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
			
			$subject = 'Cirugia Estetica Integral - Correo de Contacto';
			
			$headers = "From: " . strip_tags($_POST['email']) . "\r\n";
			$headers .= "Reply-To: ". strip_tags($_POST['email']) . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            if (mail($to, $subject, $message, $headers)) {
              echo '<hr><img src="http://cirugiaesteticaintegral.com/images/logotry_w.png" alt="Cirugia Estetica Integral" /><br>Su Mensaje ha sido enviado<hr>';
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

<style type="text/css">

/* Add whatever you need to your CSS reset */
html, body, h1, form, fieldset, input,select {
  margin: 0;
  padding: 0;
  border: none;
  }

body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; }

        #contacto {
			color: #fff;
            background: #f8b6c2;
            background: -webkit-gradient(
                            linear,
							left bottom,
							left top,
							color-stop(0.31, rgb(245,184,211)),
							color-stop(0.66, rgb(252,116,184))
                        );
            background: -moz-linear-gradient(
                            center bottom,
                            rgb(245,184,211) 31%,
                            rgb(252,116,184) 66%
                        );
            -moz-border-radius: 10px;
            -webkit-border-radius: 10px;
			border-radius: 10px;
            margin: 10px;
			width: 300px;
            }

#contacto a {
      color: #000;
      text-shadow: 0px -1px 0px #000;
      }
	  
#contacto fieldset {
      padding: 20px;
      }
	  
input.text {
      -webkit-border-radius: 15px;
      -moz-border-radius: 15px;
      border-radius: 15px;
      border:solid 1px #444;
      font-size: 14px;
      width: 90%;
      padding: 7px 8px 7px 30px;
      -moz-box-shadow: 0px 1px 0px #777;
      -webkit-box-shadow: 0px 1px 0px #777;
	  background: #fff url('img/inputSprite.png') no-repeat 4px 5px;
	  background: url('img/inputSprite.png') no-repeat 4px 5px, -moz-linear-gradient(
           center bottom,
           rgb(255,250,252) 31%,
           rgb(204,204,204) 84%
           );
	  background:  url('img/inputSprite.png') no-repeat 4px 5px, -webkit-gradient(
          	linear,
			left bottom,
			left top,
			color-stop(0.31, rgb(255,250,252)),
			color-stop(0.84, rgb(204,204,204))
          );
      color:#333;
      text-shadow:0px 1px 0px #FFF;
}
input.number {
      -webkit-border-radius: 15px;
      -moz-border-radius: 15px;
      border-radius: 15px;
      border:solid 1px #444;
      font-size: 14px;
      width: 90%;
      padding: 7px 8px 7px 30px;
      -moz-box-shadow: 0px 1px 0px #777;
      -webkit-box-shadow: 0px 1px 0px #777;
	  background: #fff url('img/inputSprite.png') no-repeat 4px 5px;
	  background: url('img/inputSprite.png') no-repeat 4px 5px, -moz-linear-gradient(
           center bottom,
           rgb(255,250,252) 31%,
           rgb(204,204,204) 84%
           );
	  background:  url('img/inputSprite.png') no-repeat 4px 5px, -webkit-gradient(
          	linear,
			left bottom,
			left top,
			color-stop(0.31, rgb(255,250,252)),
			color-stop(0.84, rgb(204,204,204))
          );
      color:#333;
      text-shadow:0px 1px 0px #FFF;
}
select.text {
      -webkit-border-radius: 15px;
      -moz-border-radius: 15px;
      border-radius: 15px;
      border:solid 1px #444;
      font-size: 14px;
      width: 90%;
      padding: 7px 8px 7px 30px;
      -moz-box-shadow: 0px 1px 0px #777;
      -webkit-box-shadow: 0px 1px 0px #777;
	  background: #fff url('img/inputSprite.png') no-repeat 4px 5px;
	  background: url('img/inputSprite.png') no-repeat 4px 5px, -moz-linear-gradient(
           center bottom,
           rgb(255,250,252) 31%,
           rgb(204,204,204) 84%
           );
	  background:  url('img/inputSprite.png') no-repeat 4px 5px, -webkit-gradient(
          	linear,
			left bottom,
			left top,
			color-stop(0.31, rgb(255,250,252)),
			color-stop(0.84, rgb(204,204,204))
          );
      color:#333;
      text-shadow:0px 1px 0px #FFF;
}

 input#email { 
 	background-position: 4px 5px; 
	background-position: 4px 5px, 0px 0px;
	}
	
 input#nombre { 
 	background-position: 4px -46px; 
	background-position: 4px -46px, 0px 0px; 
	}
 input#edad { 
 	background-position: 4px -46px; 
	background-position: 4px -46px, 0px 0px;
	width:30%;
	}
 select#sexo { 
 	background-position: 4px -106px; 
	background-position: 4px -106px, 0px 0px;
	width:50%;
	}
 input#peso {
	width:30%;
 	background-position: 4px -136px; 
	background-position: 4px -136px, 0px 0px; 
	}
 input#talla {
	width:30%;
 	background-position: 4px -166px; 
	background-position: 4px -166px, 0px 0px; 
	}
	
 input#tel { 
 	background-position: 4px -76px; 
	background-position: 4px -76px, 0px 0px; 
	}
 select#cirugia { 
 	background-position: 4px -46px; 
	background-position: 4px -46px, 0px 0px;
	}
	
#contacto h2 {
	color: #fff;
	text-shadow: 0px -1px 0px #000;
	border-bottom: solid #181818 1px;
	-moz-box-shadow: 0px 1px 0px #3a3a3a;
	text-align: center;
	padding: 18px;
	margin: 0px;
	font-weight: normal;
	font-size: 24px;
	font-family: Lucida Grande, Helvetica, Arial, sans-serif;
	}
	
#enviarMail {
	width: 203px;
	height: 40px;
	border: none;
	text-indent: -9999px;
	background: url('img/send.png') no-repeat;
	cursor: pointer;
	float: right;
	}
	
	#enviarMail:hover { background-position: 0px -41px; }
	#enviarMail:active { background-position: 0px -82px; }
	
 #contacto p {
      position: relative;
      }
	  
fieldset label.infield /* .infield label added by JS */ {
    color: #333;
    text-shadow: 0px 1px 0px #fff;
    position: absolute;
    text-align: left;
    top: 3px !important;
    left: 35px !important;
    line-height: 29px;
    }

</style>

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
