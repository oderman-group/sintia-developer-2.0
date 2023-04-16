
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
            $acomulado=0.25;
            break;
        case 2:
            $periodoActuales = "Dos";
            $acomulado=0.50;
            break;
        case 3:
            $periodoActuales = "Tres";
            $acomulado=0.75;
            break;
        case 4:
            $periodoActuales = "Final";
            $acomulado=0.10;
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
                    $sumaPromedioGeneral=0;
                    $consultaAreas= mysqli_query($conexion,"SELECT ar_id, ar_nombre, count(*) AS numMaterias, car_curso, car_grupo FROM $BD.academico_materias
                    INNER join $BD.academico_areas ON ar_id = mat_area
                    INNER JOIN $BD.academico_cargas on car_materia = mat_id and car_curso = $gradoActual AND car_grupo = $grupoActual
                    GROUP by mat_area
                    ORDER BY ar_posicion");
                    $numAreas=mysqli_num_rows($consultaAreas);
                    while($datosAreas = mysqli_fetch_array($consultaAreas, MYSQLI_BOTH)){
                        $consultaMaterias= mysqli_query($conexion,"SELECT car_id, car_ih, car_materia, 
                        mat_nombre, mat_area, mat_valor,
                        ar_nombre, ar_posicion
                        bol_estudiante, bol_periodo, bol_nota,
                        bol_nota * (mat_valor/100) AS notaArea
                        FROM $BD.academico_cargas
                        INNER JOIN $BD.academico_materias ON mat_id = car_materia
                        INNER JOIN $BD.academico_areas ON ar_id = mat_area
                        INNER JOIN $BD.academico_boletin ON bol_carga=car_id AND bol_periodo in ($condicion) AND bol_estudiante = ".$matriculadosDatos['mat_id']."
                        WHERE car_curso = ".$datosAreas['car_curso']." AND car_grupo = ".$datosAreas['car_grupo']." AND mat_area = ".$datosAreas['ar_id']."");
                        $notaArea=0;
                        while($datosMaterias = mysqli_fetch_array($consultaMaterias, MYSQLI_BOTH)){
                            //DIRECTOR DE GRUPO
                            if($datosMaterias["car_director_grupo"]==1){
                                $idDirector=$datosMaterias["car_docente"];
                            }
                            
                            //NOTA PARA LAS MATERIAS
                            $notaMateria=round($datosMaterias['bol_nota'], 1);
                            $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaMateria, $BD);

                            //AUSENCIAS EN ESTA MATERIA
                            $consultaDatosAusencias = Boletin::obtenerDatosAusencias($gradoActual, $datosMaterias['car_materia'], $periodoActual, $matriculadosDatos['mat_id'], $BD);
                            $datosAusencias = mysqli_fetch_array($consultaDatosAusencias, MYSQLI_BOTH);
                            $ausencia="";
                            if ($datosAusencias[0]>0) {
                                $ausencia= round($datosAusencias[0],0);
                            }

                            //VARIABLES NECESARIAS
                            $background='';
                            $ih=$datosMaterias["car_ih"];
                            if($datosAreas['numMaterias']>1){
                ?>
                                <tr>
                                    <td><?=$datosMaterias['mat_nombre']?></td>
                                    <td align="center"><?=$datosMaterias['car_ih']?></td>
                                    <?php
                                        for($i=1;$i<=$periodoActual;$i++){
                                            if($i!=$periodoActual){
                                    ?>
                                    <td align="center" style="background: #9ed8ed"><?=$notaMateria?></td>
                                    <?php
                                                }else{
                                    ?>
                                    <td align="center"><?=$notaMateria?></td>
                                    <td align="center"><?=$estiloNota['notip_nombre']?></td>
                                    <?php
                                            }
                                        }//FIN FOR

                                        //ACOMULADO PARA LAS MATERIAS
                                        $notaAcomuladoMateria=$notaMateria*$acomulado;
                                        $notaAcomuladoMateria= round($notaAcomuladoMateria,1);
                                        if(strlen($notaAcomuladoMateria) === 1 || $notaAcomuladoMateria == 10){
                                            $notaAcomuladoMateria = $notaAcomuladoMateria.".0";
                                        }
                                        $estiloNotaAcomuladoMaterias = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaAcomuladoMateria, $BD);
                                    ?>
                                    <td align="center"><?=$ausencia?></td>
                                    <td align="center"><?=$notaAcomuladoMateria?></td>
                                    <td align="center"><?=$estiloNotaAcomuladoMaterias['notip_nombre']?></td>
                                </tr>
                    <?php
                            $ih="";
                            $ausencia="";
                            $background='style="background: #EAEAEA"';
                            }

                            //NOTA PARA LAS AREAS
                            $notaArea+=round($datosMaterias['notaArea'], 1);
                            $estiloNotaAreas = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaArea, $BD);

                        } //FIN WHILE DE LAS MATERIAS
                        
                        //ACOMULADO PARA LAS AREAS
                        $notaAcomuladoArea=$notaArea*$acomulado;
                        $notaAcomuladoArea= round($notaAcomuladoArea,1);
                        if(strlen($notaAcomuladoArea) === 1 || $notaAcomuladoArea == 10){
                            $notaAcomuladoArea = $notaAcomuladoArea.".0";
                        }
                        $estiloNotaAcomuladoAreas = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaAcomuladoArea, $BD);
                    ?>
                    <!--********SE IMPRIME LO REFERENTE A LAS AREAS*******-->
                        <tr>
                            <td <?=$background?>><?=$datosAreas['ar_nombre']?></td>
                            <td align="center"><?=$ih?></td>
                            <?php
                                for($i=1;$i<=$periodoActual;$i++){
                                    if($i!=$periodoActual){
                            ?>
                            <td align="center" style="background: #9ed8ed"><?=$notaArea?></td>
                            <?php
                                    }else{
                            ?>
                            <td align="center"><?=$notaArea?></td>
                            <td align="center"><?=$estiloNotaAreas['notip_nombre']?></td>
                            <?php
                                    }
                                }
                            ?>
                            <td align="center"><?=$ausencia?></td>
                            <td align="center"><?=$notaAcomuladoArea?></td>
                            <td align="center"><?=$estiloNotaAcomuladoAreas['notip_nombre']?></td>
                        </tr>
                    <?php

                            //SUMA NOTAS DE LAS AREAS
                            $sumaPromedioGeneral+=$notaArea;
                            
                        } //FIN WHILE DE LAS AREAS

                        //PROMEDIO DE LAS AREAS
                        $promedioGeneral+=($sumaPromedioGeneral/$numAreas);
                        $promedioGeneral= round($promedioGeneral,1);
                        $estiloNotaPromedioGeneral = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $promedioGeneral, $BD);
                    ?>
            </tbody>
            <tfoot style="font-weight:bold; font-size: 13px;">
                <tr style="background: #EAEAEA">
                    <td colspan="2">PROMEDIO GENERAL</td>
                    <?php
                    for ($j = 1; $j <= $periodoActual; $j++) {
                        if($j!=$periodoActual){
                    ?>
                    <td align="center"><?=$promedioGeneral?></td>
                    <?php
                        }else{
                    ?>
                    <td align="center"><?=$promedioGeneral?></td>
                    <td align="center"><?=$estiloNotaPromedioGeneral['notip_nombre']?></td>
                    <?php
                        }
                    }// FIN FOR
                    ?>
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center"></td>
                </tr>
            </tfoot>
        </table>

        <p>&nbsp;</p>
        <!--******PUESTO DEL ESTUDIANTE******-->
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
        <!--******OBSERVACIONES******-->

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
        <!--******SEGUNDA PAGINA******-->
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <!--******INDICADORES POR ASIGNATURA******-->

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
        <!--******FIRMAS******-->   

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