<?php
extract($_REQUEST);
$nombreimagen=$p.".jpg";
$fondo='fondo_paquetes.jpg';
if($p=='strippers' || $p=='clases_sensualidad'){
	$fondo='fondo_alter.jpg';
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Paquetes Platinum High Class</title>
<style>
body{
	background:#f36ca7 url(<?php echo $fondo; ?>);
}
img{
	border:#000 3px solid;
}
</style>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
</head>

<body>
<center>
	<img src="<?php echo $nombreimagen; ?>">
    <div class="regresar">
   	  <object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="250" height="150">
    	  <param name="movie" value="paquetes_regresar.swf">
    	  <param name="quality" value="high">
    	  <param name="wmode" value="transparent">
    	  <param name="swfversion" value="8.0.35.0">
    	  <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
    	  <param name="expressinstall" value="Scripts/expressInstall.swf">
    	  <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
    	  <!--[if !IE]>-->
    	  <object type="application/x-shockwave-flash" data="paquetes_regresar.swf" width="250" height="150">
    	    <!--<![endif]-->
    	    <param name="quality" value="high">
    	    <param name="wmode" value="transparent">
    	    <param name="swfversion" value="8.0.35.0">
    	    <param name="expressinstall" value="Scripts/expressInstall.swf">
    	    <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
    	    <div>
    	      <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
    	      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
  	      </div>
    	    <!--[if !IE]>-->
  	    </object>
    	  <!--<![endif]-->
  	  </object>
    </div>
</center>
<script type="text/javascript">
swfobject.registerObject("FlashID");
</script>
</body>
</html>