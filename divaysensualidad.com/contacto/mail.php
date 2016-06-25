<HTML>
<BODY>
<!-- START EMAIL CODE -->
<?
// the following code will send the email
// consult your web hosting provider in case of any problems
// you can copy & paste the code into your HTML page
// copy all code between <!-- START EMAIL CODE --> and <!-- END EMAIL CODE -->
// the form will work if published online
// it will not work from your local disk

// put your email address here
// the form will be sent to this email
$to = "platinumhighclass@prodigy.net.mx";


// use echo to display information after the user has submitted the form
echo"Su informacion ha sido enviada.\n";
echo"Sus datos son: \n";

//list all fields
echo"<br>Nombre: $_POST[nombre]\n";
echo"<br>Telefono: $_POST[telefono]\n";
echo"<br>Celular: $_POST[celular]\n";
echo"<br>E_Mail: $_POST[correo]\n";
echo"<br>Servicios: $_POST[servicios]\n";
echo"<br>Productos: $_POST[productos]\n";
echo"<br>Comentario: $_POST[comentarios]\n";;

echo"<br>\n";


//send the email: $mailtxt is the content of the email

$mailtxt  ="Platinum High Class correo de ";

// use "\n" to add line breaks
$mailtxt .="\n";

$mailtxt .=" Nombre: $_POST[nombre]\n";
$mailtxt .=" Telefono: $_POST[telefono]\n";
$mailtxt .=" Celular: $_POST[celular]\n";
$mailtxt .=" E_Mail: $_POST[correo]\n";
$mailtxt .=" Servicios: $_POST[servicios]\n";
$mailtxt .=" Productos: $_POST[productos]\n";
$mailtxt .=" Comentario: $_POST[comentarios]\n";

$mailtxt .="\nEnviado desde DIVAYSENSUALIDAD.COM - CONTACTO\n";

$subject = "Correo de DivaySensualidad.com";
$headers="De: www.divaysensualidad.com <" + $to + ">\r\n";
$to2= "platinumhighclass@hotmail.com";
$to3= "neobenjax@gmail.com";
mail($to, $subject, $mailtxt, $headers);
mail($to2, $subject, $mailtxt, $headers);
mail($to3, $subject, $mailtxt, $headers);



?>
<!-- END EMAIL CODE -->
</BODY>
</HTML>