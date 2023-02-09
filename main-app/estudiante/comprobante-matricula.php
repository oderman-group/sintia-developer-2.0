<?php include("session.php"); ?>
<?php include("verificar-usuario.php"); ?>
<?php include("verificar-sanciones.php"); ?>
<?php $idPaginaInterna = 'ES0045'; ?>
<?php include("../compartido/historial-acciones-guardar.php"); ?>
<?php include("../compartido/head.php"); ?>


</head>
<!-- END HEAD -->

<body>

    <div align="center" style="margin-bottom:20px;">
        <img src="https://plataformasintia.com/icolven/compartido/enca.png"><br>

        <p style="text-align: center;"><strong>COMPROBANTE DE MATRICULA</strong></p>
        <p style="text-align: center;"><strong>A&ntilde;o 2021</strong></p>
        <p style="text-align: center;"><strong>&nbsp;</strong></p>
        <p style="text-align: center;"><strong>Nombre del estudiante:</strong><br> <?= $datosEstudianteActual["mat_primer_apellido"]." ".$datosEstudianteActual["mat_segundo_apellido"]." ".$datosEstudianteActual["mat_nombres"]; ?></p>
        <p style="text-align: center;"><strong>Grado:</strong> <br><?=$datosEstudianteActual["gra_nombre"];?></p>
        <p style="text-align: center;"><strong>Nombre del acudiente:</strong><br> <?= $datosEstudianteActual["uss_nombre"];?></p>
        <p style="text-align: center;"><strong>Direcci&oacute;n:</strong><br> <?= $datosEstudianteActual["uss_direccion"];?></p>
        
                        <p><strong>La matr&iacute;cula del estudiante se ha realizado con &eacute;xito para el a&ntilde;o&nbsp; 2021.</strong></p>
                        <p><strong>&nbsp;</strong></p>
                    
        <p style="text-align: center;"><strong>&nbsp;</strong></p>
        <p style="text-align: center;">Cra 84 No. 33 AA-01 PBX: 250 9648 &ndash; Fax: 250 8457 A.A. 877</p>
        <p style="text-align: center;">http:// <a href="http://www.icolven.edu.co">www.icolven.edu.co</a> Medell&iacute;n &ndash; Colombia</p>
        <p style="text-align: center;">C&oacute;digo DANE: 305001003513 C&oacute;digo ICFES: 000653</p>

    </div>


</body>


</html>