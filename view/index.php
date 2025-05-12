<?php
require('session.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="../images/emurcia.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Index - Emurcia</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="../templatemo_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/style.css" type="text/css" charset="utf-8"/>
<script type="text/javascript" src="../js/swfobject.js"></script>
<script type="text/javascript">

      var flashvars = {};
      flashvars.cssSource = "piecemaker.css";
      flashvars.xmlSource = "photo_list.xml";
		
      var params = {};
      params.play = "true";
      params.menu = "false";
      params.scale = "showall";
      params.wmode = "transparent";
      params.allowfullscreen = "true";
      params.allowscriptaccess = "always";
      params.allownetworking = "all";
	  
      swfobject.embedSWF('piecemaker.swf', 'piecemaker', '940', '420', '10', null, flashvars,    
      params, null);
    
</script>
</head>
<body>
<div id="templatemo_header_wrapper">
    <div id="templatemo_header">
        <div id="site_title">
          </div> <!-- end of site_title -->
            <ul id="navigation">
                <li class="logotipo"><a href=""></a></li>
                <li class="logotipo2"><a href=""></a></li>
                <li class="inicio"><a href="index.php"></a></li>
                <li class="clientes"><a href="emurciaclientes/index_clientes.php"></a></li>
                <li class="rutas"><a href="rutas/index_rutas.php"></a></li>
            </ul>
            <div class="cleaner"></div>     
        </div>
        <div class="cleaner"></div>
    </div> <!-- end of header -->
    <div class="cleaner"></div>
</div>
    
<div id="templatemo_wrapper">
    <div id="templatemo_content_top"></div>
    <div id="templatemo_content">
    	<div id="templatemo_main_content">
        	<div class="content_box">
                <h1> Administrar usuarios del sistema: </h1>
                <ul>
                  <li><a href='usuarios/adduser.php'>Agregar nuevo usuario</a></li>
                  <li><a href='usuarios/consuser.php'>Consultar usuarios</a></li>
                  <li><a href='usuarios/moduser.php?user='>Modificar usuario existente</a></li>
                  <li><a href='usuarios/deluser.php?user='>Eliminar usuario</a></li>
              </ul>
			</div>
            
            <div class="content_box last_box">

            </div>

        </div>
        
        <div id="templatemo_sidebar">
        
        	<div id="news_box">
                
                <h3>Bienvenid@ <?php echo $_SESSION["user"]; ?></h3>
                <form method="post" name="contact" action="cerrarsesion.php">
                    <input type="submit" class="submit_btn" name="submit" id="submit" value="Cerrar Sesion" />
                </form>
                
                <div class="cleaner"></div>     
            </div>
            
        </div>
        
        <div class="cleaner"></div>
    </div>
    <div id="templatemo_content_bottom"></div>
    
    <div id="templatemo_footer">

        Copyright © <a href="http://www.v2technoconsulting.com/v2technoconsulting/index.html">Techno Consulting</a> <!-- Credit: www.templatemo.com -->
    
    </div> <!-- end of templatemo_footer -->
     
</div> <!-- end of wrapper -->
<!-- templatemo 293 liquid -->
<!-- 
Liquid Template 
http://www.templatemo.com/preview/templatemo_293_liquid 
-->
</body>
</html>

