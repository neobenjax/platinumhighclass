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


if($_POST[nombre]=='' || $_POST[fecha]=='' || $_POST[telefono]=='' || $_POST[email]=='' || $_POST[invitados]=='' || $_POST[lugar]==''){
echo "Debe rellenar todos los campos del formulario";
echo"<br>Nombre: $_POST[nombre]\n";
echo"<br>fecha: $_POST[fecha]\n";
echo"<br>telefono: $_POST[telefono]\n";
echo"<br>E_Mail: $_POST[email]\n";
echo"<br>invitados: $_POST[invitados]\n";
echo"<br>Lugar: $_POST[lugar]\n";;
exit;
}

// use echo to display information after the user has submitted the form
echo"Su Registro ha sido enviado exitosamente.\n";
echo"Sus datos son: \n";

//list all fields
echo"<br>Nombre: $_POST[nombre]\n";
echo"<br>Fecha: $_POST[fecha]\n";
echo"<br>Telefono: $_POST[telefono]\n";
echo"<br>E_Mail: $_POST[email]\n";
echo"<br>Invitados: $_POST[invitados]\n";
echo"<br>Lugar: $_POST[lugar]\n";;

echo"<br>\n";


//send the email: $mailtxt is the content of the email

$mailtxt  ="Platinum High Class mail de ";

// use "\n" to add line breaks
$mailtxt .="\n";

$mailtxt .=" Nombre: $_POST[nombre]\n";
$mailtxt .=" Fecha: $_POST[fecha]\n";
$mailtxt .=" Telefono: $_POST[telefono]\n";
$mailtxt .=" E_Mail: $_POST[email]\n";
$mailtxt .=" Invitados: $_POST[invitados]\n";
$mailtxt .=" Lugar: $_POST[lugar]\n";

$mailtxt .="\nEnviado desde platinumhighclass.com - REGISTRO PARA CONCURSO\n";

$subject = "Correo de PlatinumHighClass.com Registro de Participantes";
$headers="De: www.platinumHighClass.com <" + $to + ">\r\n";
$to2= "platinumhighclass@hotmail.com";
$to3= "neobenjax@gmail.com";
mail($to, $subject, $mailtxt, $headers);
mail($to2, $subject, $mailtxt, $headers);
mail($to3, $subject, $mailtxt, $headers);



?>
<!-- END EMAIL CODE -->
</BODY>
</HTML>