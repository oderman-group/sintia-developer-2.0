
<?php
    include("../directivo/session.php");
    require_once("../class/Estudiantes.php");
    require_once("../class/Boletin.php");
    require_once("../class/Usuarios.php");
    require_once("../class/UsuariosPadre.php");
    $Plataforma = new Plataforma;

    $year=$agnoBD;
    if(isset($_REQUEST["year"])){
    $year=$_REQUEST["year"];
    }
    $BD=$_SESSION["inst"]."_".$year;

    $modulo = 1;

    if (empty($_REQUEST["periodo"])) {
        $periodoActual = 1;
    } else {
        $periodoActual = $_REQUEST["periodo"];
    }

    switch($periodoActual){
        case 1:
            $periodoActuales = "Uno";
            break;
        case 2:
            $periodoActuales = "Dos";
            break;
        case 3:
            $periodoActuales = "Tres";
            break;
        case 4:
            $periodoActuales = "Final";
            break;
    }

    if (is_numeric($_REQUEST["id"])) {
        $filtro .= " AND mat_id='" . $_REQUEST["id"] . "'";
    }

    if (is_numeric($_REQUEST["curso"])) {
        $filtro .= " AND mat_grado='" . $_REQUEST["curso"] . "'";
    }

    if(is_numeric($_REQUEST["grupo"])){
        $filtro .= " AND mat_grupo='".$_REQUEST["grupo"]."'";
    }
    $matriculadosPorCurso = Estudiantes::estudiantesMatriculados($filtro, $BD);
    $numeroEstudiantes = mysqli_num_rows($matriculadosPorCurso);
    if ($numeroEstudiantes == 0) {
    ?>
        NO HAY REGISTROS...
    <?php
        exit();
    }

    $idDirector="";
    $periodosCursados=$periodoActual-1;
    $colspan=7+$periodosCursados;
    $contadorEstudiantes=0;
    while ($matriculadosDatos = mysqli_fetch_array($matriculadosPorCurso, MYSQLI_BOTH)) {
        $gradoActual = $matriculadosDatos['mat_grado'];
        $grupoActual = $matriculadosDatos['mat_grupo'];
        switch($matriculadosDatos["gru_id"]){
            case 1:
                $grupo= "Uno";
            break;
            case 2:
                $grupo= "Dos";
            break;
            case 3:
                $grupo= "Tres";
            break;
            case 4:
                $grupo= "Sin Grupo";
            break;
        }
        //METODO QUE ME TRAE EL NOMBRE COMPLETO DEL ESTUDIANTE
        $nombreEstudainte=Estudiantes::NombreCompletoDelEstudiante($matriculadosDatos);
        //CONSULTA QUE ME TRAE LAS AREAS DEL ESTUDIANTE
        $consultaAreaEstudiante = Boletin::obtenerAreasDelEstudiante($matriculadosDatos['mat_grado'], $matriculadosDatos['mat_grupo'], $BD);
	
        if($matriculadosDatos["mat_grado"]>=12 && $matriculadosDatos["mat_grado"]<=15) {$educacion = "PREESCOLAR";}	
        elseif($matriculadosDatos["mat_grado"]>=1 && $matriculadosDatos["mat_grado"]<=5) {$educacion = "PRIMARIA";}	
        elseif($matriculadosDatos["mat_grado"]>=6 && $matriculadosDatos["mat_grado"]<=9) {$educacion = "SECUNDARIA";}
        elseif($matriculadosDatos["mat_grado"]>=10 && $matriculadosDatos["mat_grado"]<=11) {$educacion = "MEDIA";}	

?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <title>Boletín</title>
        <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
        <!-- favicon -->
        <link rel="shortcut icon" href="../sintia-icono.png" />
        <style>
            #saltoPagina {
                PAGE-BREAK-AFTER: always;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    </head>
    <body style="font-family:Arial; font-size:9px;">
        <div style="margin: 15px 0;">
            <table width="100%" cellspacing="5" cellpadding="5" border="1" rules="all" style="font-size: 13px;">
                <tr>
                    <td rowspan="2" width="20%"><img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" width="100%"></td>
                    <td align="center" rowspan="2" width="25%">
                        <h3 style="font-weight:bold; color: #00adefad; margin: 0"><?=strtoupper($informacion_inst["info_nombre"])?></h3><br>
                        <?=$informacion_inst["info_direccion"]?><br>
                        Informes: <?=$informacion_inst["info_telefono"]?>
                    </td>
                    <td>Documento:<br> <b style="color: #00adefad;"><?=number_format($matriculadosDatos["mat_documento"],0,",",".");?></b></td>
                    <td>Nombre:<br> <b style="color: #00adefad;"><?=$nombreEstudainte?></b></td>
                    <td>Grado:<br> <b style="color: #00adefad;"><?=strtoupper($matriculadosDatos["gra_nombre"]." ".$grupo)?></b></td>
                </tr>
                <tr>
                    <td>E. Básica:<br> <b style="color: #00adefad;"><?=$educacion?></b></td>
                    <td>Sede:<br> <b style="color: #00adefad;"><?=strtoupper($informacion_inst["info_nombre"])?></b></td>
                    <td>Jornada:<br> <b style="color: #00adefad;"><?=strtoupper($informacion_inst["info_jornada"])?></b></td>
                </tr>
            </table>
            <p>&nbsp;</p>
        </div>
        <table width="100%" cellspacing="5" cellpadding="5" rules="all" style="font-size: 13px;">
            <tr style="text-align:center; font-size: 13px;">
                <td style="color: #b2adad;">
                    <?php
                        $consultaEstiloNota = Boletin::listarTipoDeNotas($config["conf_notas_categoria"], $BD);
                        $numEstiloNota=mysqli_num_rows($consultaEstiloNota);
                        $i=1;
                        while($estiloNota = mysqli_fetch_array($consultaEstiloNota, MYSQLI_BOTH)){
                            $diagonal=" / ";
                            if($i==$numEstiloNota){
                                $diagonal="";
                            }
                            echo $estiloNota['notip_nombre'].": ".$estiloNota['notip_desde']." - ".$estiloNota['notip_hasta'].$diagonal;
                            $i++;
                        }
                    ?>
                </td>
            </tr>
        </table>
        <table width="100%" rules="all" border="1" style="font-size: 15px;">
            <thead style="background-color: #00adefad;">
                <tr style="font-weight:bold; text-align:center;">
                    <td width="20%" rowspan="2">ASIGNATURAS</td>
                    <td width="3%" rowspan="2">I.H</td>
                    <?php
                        if($periodoActual!=1){
                    ?>
                    <td width="3%" colspan="<?=$periodosCursados?>"><a href="#" style="color:#000; text-decoration:none;">Periodo Cursados</a></td>
                    <?php
                        }
                    ?>
                    <td width="3%" colspan="2">Periodo Actual (<?=strtoupper($periodoActuales)?>)</td>
                    <td width="3%" colspan="3">TOTAL ACUMULADO</td>
                </tr>
                <tr style="font-weight:bold; text-align:center;">
                    <?php
                        for($i=1;$i<=$periodoActual;$i++){
                            if($i!=$periodoActual){
                    ?>
                        <td width="3%"><?=$i?></td>
                    <?php
                        }else{
                    ?>
                    <td width="3%">Nota</td>
                    <td width="3%">Desempeño</td>
                    <?php
                            }
                        }
                    ?>
                    <td width="3%">Fallas</td>
                    <td width="3%">Nota</td>
                    <td width="3%">Desempeño</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $contador=1;
                $contadorAreas=1;
                while ($area = mysqli_fetch_array($consultaAreaEstudiante, MYSQLI_BOTH)) {
                    switch($periodoActual){
                        case 1:
                            $condicion = "1";
                            $condicion2 = "1";
                            break;
                        case 2:
                            $condicion = "1,2";
                            $condicion2 = "2";
                            break;
                        case 3:
                            $condicion = "1,2,3";
                            $condicion2 = "3";
                            break;
                        case 4:
                            $condicion = "1,2,3,4";
                            $condicion2 = "4";
                            break;
                    }

                    //CONSULTA QUE ME TRAE LA DEFINITIVA POR MATERIA Y NOMBRE DE LA MATERIA
                    $consultaDefinitivaNombreMateria = Boletin::obtenerDefinitivaYnombrePorMateria($matriculadosDatos['mat_id'], $area["ar_id"], $condicion, $BD);
                    $numMateria=mysqli_num_rows($consultaDefinitivaNombreMateria);
                    if($numMateria>1){
                        while ($materia = mysqli_fetch_array($consultaDefinitivaNombreMateria, MYSQLI_BOTH)) {
                            if($materia["car_director_grupo"]==1){
                                $idDirector=$materia["car_docente"];
                            }
                ?>
                <!--********SE IMPRIME LO REFERENTE A LAS MATERIAS*******-->
                <tr>
                    <td><?=$materia["mat_nombre"]?></td>
                    <td align="center"><?=$materia["car_ih"]?></td>
                    <?php
                        $promedioMateria = 0;
                        for($i=1;$i<=$periodoActual;$i++){
                            $consultaBoletin=Boletin::obtenerObservaciones($materia["car_id"], $i, $matriculadosDatos['mat_id'], $BD);
                            $datosBoletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);

                            $notaBoletin=0;
                            if (!empty($datosBoletin['bol_nota'])) {
                                $notaBoletin = (round($datosBoletin['bol_nota'], 1));
                            }

                            $promedioMateria += $notaBoletin;
                            if($i!=$periodoActual){
                    ?>
                    <td align="center" style="background: #9ed8ed"><?=$notaBoletin?></td>
                    <?php
                                }else{
                                $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaBoletin, $BD);
                    ?>
                    <td align="center"><?=$notaBoletin?></td>
                    <td align="center"><?=$estiloNota['notip_nombre']?></td>
                    <?php
                            }
                        }//FIN FOR
                        $promedioMateria = ($promedioMateria / $periodoActual);
                        $promedioMateria = round($promedioMateria, 1);
                        $promedioMateriaFinal = $promedioMateria;

                        // SI PERDIÓ LA MATERIA A FIN DE AÑO
                        if ($promedioMateria < $config["conf_nota_minima_aprobar"]) {
                            $consultaNivelacion = Boletin::obtenerNivelaciones($materia['car_id'], $matriculadosDatos['mat_id'], $BD);
                            $nivelacion = mysqli_fetch_array($consultaNivelacion, MYSQLI_BOTH);

                            $promedioMateriaFinal = $nivelacion['niv_definitiva'];
                            $promedioMateriaFinal = ($promedioMateriaFinal*$formula);
                        }

                        $promediosMateriaEstiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $promedioMateriaFinal, $BD);
                        
                        $consultaDatosAusencias = Boletin::obtenerDatosAusencias($gradoActual, $materia['mat_id'], $periodoActual, $matriculadosDatos['mat_id'], $BD);
                        $datosAusencias = mysqli_fetch_array($consultaDatosAusencias, MYSQLI_BOTH);
                        $ausencia="";
                        if ($datosAusencias[0]>0) {
                            $ausencia= round($datosAusencias[0],0);
                        }
                    ?>
                    <td align="center"><?=$ausencia;?></td>
                    <td align="center"><?=$promedioMateriaFinal;?></td>
                    <td align="center"><?=$promediosMateriaEstiloNota['notip_nombre'];?></td>
                </tr>
                <?php
                        $contador++;
                        }//FIN WHILE MATERIAS
                    }
                    //CONSULTA QUE ME TRAE EL NOMBRE Y EL PROMEDIO DEL AREA
                    $consultaArea = Boletin::obtenerDatosDelArea($matriculadosDatos['mat_id'], $area["ar_id"], $condicion, $BD);
                    $datosArea = mysqli_fetch_array($consultaArea, MYSQLI_BOTH);
                    
                    $background='';
                    $ih=$datosArea["car_ih"];
                    if($numMateria>1){
                        $ih="";
                        $background='style="background: #EAEAEA"';
                    }
                ?>
                <!--********SE IMPRIME LO REFERENTE A LAS AREAS*******-->
                <tr>
                    <td <?=$background?>><?=$datosArea["ar_nombre"]?></td>
                    <td align="center"><?=$ih?></td>
                    <?php
                        for($i=1;$i<=$periodoActual;$i++){
                            // while($materiaArea = mysqli_fetch_array($consultaDefinitivaNombreMateria, MYSQLI_BOTH)){
                            // $formula=($materiaArea['mat_valor']/100);
                            
                            // if($numMateria==1){
                                $consultaBoletinArea=Boletin::obtenerObservaciones($datosArea["car_id"], $i, $matriculadosDatos['mat_id'], $BD);
                                $datosBoletinArea = mysqli_fetch_array($consultaBoletinArea, MYSQLI_BOTH);

                                $notaBoletinArea=0;
                                if (!empty($datosBoletinArea['bol_nota'])) {
                                    $notaBoletinArea = (round($datosBoletinArea['bol_nota'], 1));
                                }
                                $notaGeneralArea=($notaBoletinArea*$formula);
                                $notaArea=round($notaGeneralArea, 1);
                            // }
                            if($i!=$periodoActual){
                    ?>
                    <td align="center" style="background: #9ed8ed"><?=$notaArea?></td>
                    <?php
                            }else{
                        $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaArea, $BD);
                    ?>
                    <td align="center"><?=$notaArea?></td>
                    <td align="center"><?=$estiloNota['notip_nombre'];?></td>
                    <?php
                            }
                        }
                    ?>
                    <td align="center"><?=$ausencia;?></td>
                    <td align="center"><?=$promedioMateriaFinal;?></td>
                    <td align="center"><?=$promediosMateriaEstiloNota['notip_nombre'];?></td>
                </tr>
                <?php
                    $contadorAreas++;
                    }//FIN WHILE AREAS
                ?>
            </tbody>
            <tfoot style="font-weight:bold; font-size: 13px;">
                <tr style="background: #9ed8ed">
                    <td colspan="<?=$colspan?>"></td>
                </tr>
                <tr style="background: #9ed8ed">
                    <td colspan="<?=$colspan?>"></td>
                </tr>
                <tr style="background: #EAEAEA">
                    <td colspan="2">PROMEDIO GENERAL</td>
                    <?php
                    $promedioFinal = 0;
                    for ($j = 1; $j <= $periodoActual; $j++) {
                        switch($j){
                            case 1:
                                $promediosPeriodos=$promedioGeneral1;
                                break;
                            case 2:
                                $promediosPeriodos=$promedioGeneral2;
                                break;
                            case 3:
                                $promediosPeriodos=$promedioGeneral3;
                                break;
                            case 4:
                                $promediosPeriodos=$promedioGeneral4;
                                break;
                        }
                        $promediosPeriodos = $promediosPeriodos/($contador-1);
                        $promediosPeriodos = round($promediosPeriodos,1);
                        if($j!=$periodoActual){
                    ?>
                    <td align="center"><?=$promediosPeriodos;?></td>
                    <?php
                        }else{
                        $promedioEstiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $promediosPeriodos, $BD);
                    ?>
                    <td align="center"><?=$promediosPeriodos;?></td>
                    <td align="center"><?=$promedioEstiloNota['notip_nombre'];?></td>
                    <?php
                            }
                        $promedioFinal +=$promediosPeriodos;
                    }// FIN FOR

                    $promedioFinal = round($promedioFinal/$periodoActual,1);
                    $promedioFinalEstiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $promedioFinal, $BD);
                    ?>
                    <td align="center"></td>
                    <td align="center"><?=$promedioFinal;?></td>
                    <td align="center"><?= $promedioFinalEstiloNota['notip_nombre']; ?></td>
                </tr>
            </tfoot>
        </table>

        <p>&nbsp;</p>

        <table style="font-size: 15px;" width="80%" cellspacing="5" cellpadding="5" rules="all" border="1" align="right">
            <tr style="background-color: #EAEAEA;">
                <?php
                    if(empty($_REQUEST["curso"])){
                        $filtro = " AND mat_grado='" . $gradoActual . "' AND mat_grupo='".$grupoActual."'";
                        $matriculadosDelCurso = Estudiantes::estudiantesMatriculados($filtro, $BD);
                        $numeroEstudiantes = mysqli_num_rows($matriculadosDelCurso);
                    }
                    //Buscamos Puesto del estudiante en el curso
                    $puestoEstudiantesCurso = 0;
                    $puestosCursos = Boletin::obtenerPuestoYpromedioEstudiante($periodoActual, $gradoActual, $grupoActual, $BD);
                    
                    while($puestoCurso = mysqli_fetch_array($puestosCursos, MYSQLI_BOTH)){
                        if($puestoCurso['bol_estudiante']==$matriculadosDatos['mat_id']){
                            $puestoEstudiantesCurso = $puestoCurso['puesto'];
                        }
                    }
                    
                    //Buscamos Puesto del estudiante en la institución
                    $matriculadosDeLaInstitucion = Estudiantes::estudiantesMatriculados("", $BD);
                    $numeroEstudiantesInstitucion = mysqli_num_rows($matriculadosDeLaInstitucion);

                    $puestoEstudiantesInstitucion = 0;
                    $puestosInstitucion = Boletin::obtenerPuestoEstudianteEnInstitucion($periodoActual, $BD);
                    
                    while($puestoInstitucion = mysqli_fetch_array($puestosInstitucion, MYSQLI_BOTH)){
                        if($puestoInstitucion['bol_estudiante']==$matriculadosDatos['mat_id']){
                            $puestoEstudiantesInstitucion = $puestoInstitucion['puesto'];
                        }
                    }
                ?>
                <td align="center" width="40%">Puesto en el curso <b><?=$puestoEstudiantesCurso?></b> entre <b><?=$numeroEstudiantes?></b> Estudiantes.</td>
                <td align="center" width="40%">Puesto en el colegio <b><?=$puestoEstudiantesInstitucion?></b> entre <b><?=$numeroEstudiantesInstitucion?></b> Estudiantes.</td>
            </tr>
        </table>

        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>

        <table style="font-size: 15px;" width="100%" cellspacing="5" cellpadding="5" rules="all" border="1" align="center">
            <thead>
                <tr style="font-weight:bold; text-align:left; background-color: #00adefad;">
                    <td><b>Observaciones:</b></td>
                </tr>
            </thead>
            <tbody>
                <tr style="color:#000;">
                    <td style="padding-left: 20px;">
                        <?php 
                            $cndisiplina = mysqli_query($conexion, "SELECT * FROM $BD.disiplina_nota WHERE dn_cod_estudiante='".$matriculadosDatos[0]."' AND dn_periodo='".$periodoActual."'");
                            while($rndisiplina=mysqli_fetch_array($cndisiplina, MYSQLI_BOTH)){

                                if(!empty($rndisiplina['dn_observacion'])){
                                    $explode=explode(",",$rndisiplina['dn_observacion']);
                                    $numDatos=count($explode);
                                    if($numDatos>0 && ctype_digit($explode[0])){
                                        for($i=0;$i<$numDatos;$i++){
                                            $consultaObservaciones = mysqli_query($conexion, "SELECT * FROM $BD.academico_observaciones WHERE obser_id=$explode[$i]");
                                            $observaciones = mysqli_fetch_array($consultaObservaciones, MYSQLI_BOTH);
                                            echo "- ".$observaciones['obser_descripcion']."<br>";
                                        }
                                    }else{
                                        echo "- ".$rndisiplina["dn_observacion"]."<br>";
                                    }
                                }
                            }
                        ?>
                        <p>&nbsp;</p>
                    </td>
                </tr>
            </tbody>
        </table>

        <div id="saltoPagina"></div>
        <p>&nbsp;</p>
        <p>&nbsp;</p>

        <table width="100%" cellspacing="5" cellpadding="5" rules="all" border="1" align="center">
            <thead>
                <tr style="font-weight:bold; text-align:center; background-color: #00adefad;">
                    <td width="30%">Asignatura</td>
                    <td width="70%">Indicadores de desempeño</td>
                </tr>
            </thead>

            <?php
            $conCargasDos = mysqli_query($conexion, "SELECT * FROM $BD.academico_cargas
	        INNER JOIN $BD.academico_materias ON mat_id=car_materia
	        WHERE car_curso='" . $gradoActual . "' AND car_grupo='" . $grupoActual . "'");
            while ($datosCargasDos = mysqli_fetch_array($conCargasDos, MYSQLI_BOTH)) {

                
            ?>
                <tbody>
                    <tr style="color:#000;">
                        <td><?= $datosCargasDos['mat_nombre']; ?><br><span style="color:#C1C1C1;"><?= $datosCargasDos['uss_nombre']; ?></span></td>
                        <td>
                        
                            <?php
                            //INDICADORES
                            $indicadores = mysqli_query($conexion, "SELECT * FROM $BD.academico_indicadores_carga 
		                    INNER JOIN $BD.academico_indicadores ON ind_id=ipc_indicador
		                    WHERE ipc_carga='" . $datosCargasDos['car_id'] . "' AND ipc_periodo='" . $periodoActual . "'");
                            while ($indicador = mysqli_fetch_array($indicadores, MYSQLI_BOTH)) {
                            ?>
                   
                        <?= $indicador['ind_nombre']; ?><br>
                    
                <?php
                            }
                ?>
                    </td>
                </tr>
                </tbody>
            <?php
            }
            ?>
        </table>

        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>      

        <table width="100%" cellspacing="0" cellpadding="0" rules="none" border="0" style="text-align:center; font-size:10px;">
            <tr>
                <td align="center">
                    <?php
                        $directorGrupo = Usuarios::obtenerDatosUsuario($idDirector);
                        $nombreDirectorGrupo = UsuariosPadre::nombreCompletoDelUsuario($directorGrupo);
                        if(!empty($directorGrupo["uss_firma"])){
                            echo '<img src="../files/fotos/'.$directorGrupo["uss_firma"].'" width="100"><br>';
                        }else{
                            echo '<p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>';
                        }
                    ?>
                    <p style="height:0px;"></p>_________________________________<br>
                    <p>&nbsp;</p>
                    <?=$nombreDirectorGrupo?><br>
                    Director(a) de grupo
                </td>
                <td align="center">
                    <?php
                        $rector = Usuarios::obtenerDatosUsuario($informacion_inst["info_rector"]);
                        $nombreRector = UsuariosPadre::nombreCompletoDelUsuario($rector);
                        if(!empty($rector["uss_firma"])){
                            echo '<img src="../files/fotos/'.$rector["uss_firma"].'" width="100"><br>';
                        }else{
                            echo '<p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>';
                        }
                    ?>
                    <p style="height:0px;"></p>_________________________________<br>
                    <p>&nbsp;</p>
                    <?=$nombreRector?><br>
                    Rector(a)
                </td>
            </tr>
        </table>

        <p>&nbsp;</p>
        <div align="center" style="font-size:10px; margin-top:5px; margin-bottom: 10px;">
            <img src="<?=$Plataforma->logo;?>" height="50"><br>
            ESTE DOCUMENTO FUE GENERADO POR:<br>
            SINTIA - SISTEMA INTEGRAL DE GESTI&Oacute;N INSTITUCIONAL
        </div>

        <?php
            $contadorEstudiantes++;
            if($contadorEstudiantes!=$numeroEstudiantes && empty($_GET['id'])){
        ?>

        <div id="saltoPagina"></div>
<?php
            }
    }//FIN WHILE MATRICULADOS
?>

        <script type="application/javascript">
            print();
        </script>
    </body>
</html>