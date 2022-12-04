<?php
session_start();
include("../modelo/conexion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Plataforma SINTIA - Versiculo</title>
<style type="text/css">
*{
	font-family:Arial;
}
html,
body { 
  height: 100%; 
  margin: 0; 
  padding: 0; 
  text-align: center;
  background:#EBEBEB;
}
 
body {
  display: table;
  width: 100%;
}
header{
	background:#090;
	color:#FFF;
}
.titulo{
	padding:10px;
}
.antes{
	text-align:justify;
	font-size:16px;
}
.antes a{
	font-weight:bold;
	color:#000;
}
.promesa{
	background:#5D5D5D;
	color:#FFF;
	font-size:16px;
	padding:20px;
	border-radius:20px 5px 20px 5px;
	margin:20px;
	transition: 1s;
}
.promesa:hover{
	background:#2E2E2E;
	transform:scale(1.3);
	font-size:18px;
	transition-timing-function:linear;
	opacity:0.95;
}
.contenedor{
	margin:10px;
	display:flex;
}
.si{
	background:#FFF;
	padding:20px;
	width:20%;
	margin:2px;
}
.sc{
	background:#FFF;
	padding:20px;
	width:60%;
	margin:2px;
}
.sd{
	background:#FFF;
	padding:20px;
	width:20%;
	margin:2px;
}
.btn-1{
	background:#099;
	color:#FFF;
	padding:10px;
	margin:10px;
	border-radius:2px;
	text-decoration:none;
	border:none;
	cursor:pointer;
}
.btn-2{
	background:#F36;
	color:#FFF;
	padding:10px;
	margin:10px;
	border-radius:2px;
	text-decoration:none;
	border:none;
	cursor:pointer;
}
.menui li{
	display:block;
	margin-bottom:10px;
	text-align:left;
}
.menui a{
	color:#000;
	font-size:14px;
}
.menui img{
	border-radius:5px;
	width:80%;
}
footer{
	background:#090;
	color:#FFF;
	height:100px;
	display:table-row;
}
footer a{
	color:#FFF;
}
</style>
</head>

<body>
	<header>
        	<div align="center" class="titulo"><h1 align="center">LA PROMESA DE DIOS PARA TÍ HOY</h1></div>
    </header>
    <div class="contenedor">
        <section class="si">
        	<nav class="menui">
                <h3 align="center">¿Qué deseas hacer?</h3>
                <p align="center"><img src="../../dadyd/img/llamanosdadyd.png" /></p>
                <ul>
                    <li><a href="">Continuar a SINTIA</a></li>
                    <li><a href="">Recordar esta promesa en SINTIA</a></li>
                    <li><a href="">Dejar de recibir estas promesas</a></li>
                </ul>
            </nav>
        </section>
        
        <section class="sc">
            <div class="antes">
            	<p>Antes que empieces a utilizar los módulos de SINTIA queremos regalarte una de las promesas que Dios tiene para ti. En el momento que desees puedes <a href="">saltar esta pantalla</a> y continuar a SINTIA.</p>
                <p>En la columna izquierda puedes escoger cualquiera de las opciones que desees.</p>
            </div>
            <div class="promesa">
                Jhon Oderman (<?=$_SESSION["id"];?>)<br />
                Esfuerzate y sé valiente. No temas ni desmayes porque Jehová tu Dios estará contigo dondequiera que vayas.<br />
                - Josué 1:9	
            </div>
            <div>
            	<a href="../directivo/index.php" class="btn-1">Continuar a SINTIA</a>
            </div>
        </section>
        
        <section class="sd">
        	<h3 align="center">Comparte con un amig@</h3>
            <form>
            	<p><input placeholder="Nombre" /></p>
                <p><input placeholder="Email" /></p>
                <p><input type="submit" value="Compartir" class="btn-1" /></p>
            </form>
        </section>
    </div>
    
    <footer>
        <figure>
        	<img src="../files/images/logo.png" width="250" /><br />
            SINTIA &copy; COPYRIGTH 2016<br>
            <a href="https://plataformasintia.com/">www.plataformasintia.com</a>
        </figure>
    </footer>
</body>
</html>