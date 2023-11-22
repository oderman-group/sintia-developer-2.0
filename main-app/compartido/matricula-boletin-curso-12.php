
<?php
    session_start();
    include("../../config-general/config.php");
    include("../../config-general/consulta-usuario-actual.php");
    require_once("../class/Estudiantes.php");
    require_once("../class/Boletin.php");
    require_once("../class/Usuarios.php");
    require_once("../class/UsuariosPadre.php");
    $Plataforma = new Plataforma;

    $year=$_SESSION["bd"];
    if(isset($_REQUEST["year"])){
    $year=base64_decode($_REQUEST["year"]);
    }
    $BD=$_SESSION["inst"]."_".$year;

    $modulo = 1;

    if (empty($_REQUEST["periodo"])) {
        $periodoActual = 1;
    } else {
        $periodoActual = base64_decode($_REQUEST["periodo"]);
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
            $acomulado=1;
            break;
    }

    $filtro = "";
    if (!empty($_REQUEST["id"])) {
        $filtro .= " AND mat_id='" . base64_decode($_REQUEST["id"]) . "'";
    }

    if (!empty($_REQUEST["curso"])) {
        $filtro .= " AND mat_grado='" . base64_decode($_REQUEST["curso"]) . "'";
    }

    if(!empty($_REQUEST["grupo"])){
        $filtro .= " AND mat_grupo='".base64_decode($_REQUEST["grupo"])."'";
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
        $promedioGeneral = 0;
        $promedioGeneralPeriodos = 0;
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
                        Informes: <?=$informacion_inst["info_telefono"]?><br><br>
                        AÑO LECTIVO: <?=$year?>
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
                    $consultaAreas= mysqli_query($conexion,"SELECT ar_id, ar_nombre, count(*) AS numMaterias, car_curso, car_grupo FROM ".BD_ACADEMICA.".academico_materias am
                    INNER join ".BD_ACADEMICA.".academico_areas a ON a.ar_id = am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$year}
                    INNER JOIN $BD.academico_cargas on car_materia = am.mat_id and car_curso = $gradoActual AND car_grupo = $grupoActual
                    WHERE am.institucion={$config['conf_id_institucion']} AND am.year={$year}
                    GROUP by am.mat_area
                    ORDER BY a.ar_posicion");
                    $numAreas=mysqli_num_rows($consultaAreas);
                    $sumaPromedioGeneral=0;
                    $sumaPromedioGeneralPeriodo1=0;
                    $sumaPromedioGeneralPeriodo2=0;
                    $sumaPromedioGeneralPeriodo3=0;
                    while($datosAreas = mysqli_fetch_array($consultaAreas, MYSQLI_BOTH)){

                        $consultaMaterias= mysqli_query($conexion,"SELECT car_id, car_ih, car_materia, car_docente, car_director_grupo,
                        mat_nombre, mat_area, mat_valor,
                        ar_nombre, ar_posicion
                        bol_estudiante, bol_periodo, bol_nota,
                        bol_nota * (mat_valor/100) AS notaArea
                        FROM $BD.academico_cargas
                        INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id = car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$year}
                        INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id = am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$year}
                        INNER JOIN ".BD_ACADEMICA.".academico_boletin bol ON bol_carga=car_id AND bol_periodo ='".$periodoActual."' AND bol_estudiante = '".$matriculadosDatos['mat_id']."' AND bol.institucion={$config['conf_id_institucion']} AND bol.year={$year}
                        WHERE car_curso = ".$datosAreas['car_curso']." AND car_grupo = ".$datosAreas['car_grupo']." AND am.mat_area = ".$datosAreas['ar_id']."");
                        $notaArea=0;
                        $notaAreasPeriodos=0;
                        while($datosMaterias = mysqli_fetch_array($consultaMaterias, MYSQLI_BOTH)){
                            //DIRECTOR DE GRUPO
                            if($datosMaterias["car_director_grupo"]==1){
                                $idDirector=$datosMaterias["car_docente"];
                            }

                            //NOTA PARA LAS MATERIAS
                            $notaMateria=round($datosMaterias['bol_nota'], 1);
                            $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaMateria, $BD);
                            if($notaMateria<10){
                                $estiloNota['notip_nombre']="Bajo";
                            }
                            if($notaMateria>50){
                                $estiloNota['notip_nombre']="Superior";
                            }

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
                                        $notaMateriasPeriodosTotal=0;
                                        for($i=1;$i<=$periodoActual;$i++){
                                            if($i!=$periodoActual){
                                                $consultaPeriodos=mysqli_query($conexion,"SELECT * FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_carga='".$datosMaterias['car_id']."' AND bol_periodo='".$i."' AND bol_estudiante = '".$matriculadosDatos['mat_id']."' AND institucion={$config['conf_id_institucion']} AND year={$year}");
                                                $datosPeriodos=mysqli_fetch_array($consultaPeriodos, MYSQLI_BOTH);
                                                $notaMateriasPeriodos=$datosPeriodos['bol_nota'];
                                                $notaMateriasPeriodos=round($notaMateriasPeriodos, 1);
                                                $notaMateriasPeriodosTotal+=$notaMateriasPeriodos;

                                                $notaMateriasPeriodosFinal=$notaMateriasPeriodos;
                                                if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                                                    $estiloNotaAreas = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaMateriasPeriodos, $BD);
                                                    $notaMateriasPeriodosFinal= !empty($estiloNotaAreas['notip_nombre']) ? $estiloNotaAreas['notip_nombre'] : "";
                                                    if($notaMateriasPeriodos<10){
                                                        $notaMateriasPeriodosFinal="Bajo";
                                                    }
                                                    if($notaMateriasPeriodos>50){
                                                        $notaMateriasPeriodosFinal="Superior";
                                                    }
                                                }
                                    ?>
                                    <td align="center" style="background: #9ed8ed"><?=$notaMateriasPeriodosFinal?></td>
                                    <?php
                                                }else{
                                    ?>
                                    <td align="center"><?=$notaMateria?></td>
                                    <td align="center"><?=$estiloNota['notip_nombre']?></td>
                                    <?php
                                            }
                                        }//FIN FOR

                                        //ACOMULADO PARA LAS MATERIAS
                                        $notaAcomuladoMateria=($notaMateria+$notaMateriasPeriodosTotal)/$config["conf_periodos_maximos"];
                                        $notaAcomuladoMateria= round($notaAcomuladoMateria,1);
                                        if(strlen($notaAcomuladoMateria) === 1 || $notaAcomuladoMateria == 10){
                                            $notaAcomuladoMateria = $notaAcomuladoMateria.".0";
                                        }
                                        $estiloNotaAcomuladoMaterias = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaAcomuladoMateria, $BD);
                                        if($notaAcomuladoMateria<10){
                                            $estiloNotaAcomuladoMaterias['notip_nombre']="Bajo";
                                        }
                                        if($notaAcomuladoMateria>50){
                                            $estiloNotaAcomuladoMaterias['notip_nombre']="Superior";
                                        }
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
                            if(!empty($datosMaterias['notaArea'])) $notaArea+=round($datosMaterias['notaArea'], 1);

                        } //FIN WHILE DE LAS MATERIAS
                    ?>
                    <!--********SE IMPRIME LO REFERENTE A LAS AREAS*******-->
                        <tr>
                            <td <?=$background?>><?=$datosAreas['ar_nombre']?></td>
                            <td align="center"><?=$ih?></td>
                            <?php
                                $notaAreasPeriodosTotal=0;
                                $promGeneralPer1=0;
                                $promGeneralPer2=0;
                                $promGeneralPer3=0;
                                for($i=1;$i<=$periodoActual;$i++){
                                    if($i!=$periodoActual){
                                        $consultaAreasPeriodos=mysqli_query($conexion,"SELECT mat_valor,
                                        bol_estudiante, bol_periodo, bol_nota,
                                        SUM(bol_nota * (mat_valor/100)) AS notaArea
                                        FROM academico_cargas
                                        INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id = car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$year}
                                        INNER JOIN ".BD_ACADEMICA.".academico_boletin bol ON bol_carga=car_id AND bol_periodo='".$i."' AND bol_estudiante = '".$matriculadosDatos['mat_id']."' AND bol.institucion={$config['conf_id_institucion']} AND bol.year={$year}
                                        WHERE am.mat_area = ".$datosAreas['ar_id']."
                                        GROUP BY am.mat_area");
                                        $datosAreasPeriodos=mysqli_fetch_array($consultaAreasPeriodos, MYSQLI_BOTH);
                                        if(!empty($datosAreasPeriodos['notaArea'])) $notaAreasPeriodos=round($datosAreasPeriodos['notaArea'], 1);
                                        $notaAreasPeriodosTotal+=$notaAreasPeriodos;
                                        switch($i){
                                            case 1:
                                                $promGeneralPer1+=$notaAreasPeriodos;
                                                break;
                                            case 2:
                                                $promGeneralPer2+=$notaAreasPeriodos;
                                                break;
                                            case 3:
                                                $promGeneralPer3+=$notaAreasPeriodos;
                                                break;
                                        }

                                        $notaAreasPeriodosFinal=$notaAreasPeriodos;
                                        if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                                            $estiloNotaAreas = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaAreasPeriodos, $BD);
                                            $notaAreasPeriodosFinal= !empty($estiloNotaAreas['notip_nombre']) ? $estiloNotaAreas['notip_nombre'] : "";
                                            if($notaAreasPeriodos<10){
                                                $notaAreasPeriodosFinal="Bajo";
                                            }
                                            if($notaAreasPeriodos>50){
                                                $notaAreasPeriodosFinal="Superior";
                                            }
                                        }
                            ?>
                            <td align="center" style="background: #9ed8ed"><?=$notaAreasPeriodosFinal?></td>
                            <?php
                                    }else{
                                        $estiloNotaAreas = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaArea, $BD);
                                        if($notaArea<10){
                                            $estiloNotaAreas['notip_nombre']="Bajo";
                                        }
                                        if($notaArea>50){
                                            $estiloNotaAreas['notip_nombre']="Superior";
                                        }
                            ?>
                            <td align="center"><?=$notaArea?></td>
                            <td align="center"><?=$estiloNotaAreas['notip_nombre']?></td>
                            <?php
                                    }
                                }
                        
                                //ACOMULADO PARA LAS AREAS
                                $notaAcomuladoArea=($notaArea+$notaAreasPeriodosTotal)/$config["conf_periodos_maximos"];
                                $notaAcomuladoArea= round($notaAcomuladoArea,1);
                                if(strlen($notaAcomuladoArea) === 1 || $notaAcomuladoArea == 10){
                                    $notaAcomuladoArea = $notaAcomuladoArea.".0";
                                }
                                $estiloNotaAcomuladoAreas = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaAcomuladoArea, $BD);
                                if($notaAcomuladoArea<10){
                                    $estiloNotaAcomuladoAreas['notip_nombre']="Bajo";
                                }
                                if($notaAcomuladoArea>50){
                                    $estiloNotaAcomuladoAreas['notip_nombre']="Superior";
                                }
                            ?>
                            <td align="center"><?=$ausencia?></td>
                            <td align="center"><?=$notaAcomuladoArea?></td>
                            <td align="center"><?=$estiloNotaAcomuladoAreas['notip_nombre']?></td>
                        </tr>
                    <?php

                            //SUMA NOTAS DE LAS AREAS
                            $sumaPromedioGeneral+=$notaArea;

                            //SUMA NOTAS DE LAS AREAS PERIODOS ANTERIORES
                            $sumaPromedioGeneralPeriodo1+=$promGeneralPer1;
                            $sumaPromedioGeneralPeriodo2+=$promGeneralPer2;
                            $sumaPromedioGeneralPeriodo3+=$promGeneralPer3;
                            
                        } //FIN WHILE DE LAS AREAS

                        //PROMEDIO DE LAS AREAS
                        $promedioGeneral+=($sumaPromedioGeneral/$numAreas);
                        $promedioGeneral= round($promedioGeneral,1);
                        $estiloNotaPromedioGeneral = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $promedioGeneral, $BD);
                        if($promedioGeneral<10){
                            $estiloNotaPromedioGeneral['notip_nombre']="Bajo";
                        }
                        
                    ?>
            </tbody>
            <tfoot style="font-weight:bold; font-size: 13px;">
                <tr style="background: #EAEAEA">
                    <td colspan="2">PROMEDIO GENERAL</td>
                    <?php
                    for ($j = 1; $j <= $periodoActual; $j++) {
                        if($j!=$periodoActual){
                            switch($j){
                                case 1:
                                    $sumaPromedioGeneralPeriodos=$sumaPromedioGeneralPeriodo1;
                                    break;
                                case 2:
                                    $sumaPromedioGeneralPeriodos=$sumaPromedioGeneralPeriodo2;
                                    break;
                                case 3:
                                    $sumaPromedioGeneralPeriodos=$sumaPromedioGeneralPeriodo3;
                                    break;
                            }

                            //PROMEDIO DE LAS AREAS PERIODOS ANTERIORES
                            $promedioGeneralPeriodos=($sumaPromedioGeneralPeriodos/$numAreas);
                            $promedioGeneralPeriodos= round($promedioGeneralPeriodos,1);

                            $promedioGeneralPeriodosFinal=$promedioGeneralPeriodos;
                            if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                                $estiloNotaAreas = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $promedioGeneralPeriodos, $BD);
                                $promedioGeneralPeriodosFinal= !empty($estiloNotaAreas['notip_nombre']) ? $estiloNotaAreas['notip_nombre'] : "";
                                if($promedioGeneralPeriodos<10){
                                    $promedioGeneralPeriodosFinal="Bajo";
                                }
                                if($promedioGeneralPeriodos>50){
                                    $promedioGeneralPeriodosFinal="Superior";
                                }
                            }
                    ?>
                    <td align="center"><?=$promedioGeneralPeriodosFinal;?></td>
                    <?php
                        }else{
                    ?>
                    <td align="center"><?=$promedioGeneral;?></td>
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
                            $cndisiplina = mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disiplina_nota WHERE dn_cod_estudiante='".$matriculadosDatos[0]."' AND dn_periodo='".$periodoActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                            while($rndisiplina=mysqli_fetch_array($cndisiplina, MYSQLI_BOTH)){

                                if(!empty($rndisiplina['dn_observacion'])){
                                    if($config['conf_observaciones_multiples_comportamiento'] == '1'){
                                        $explode=explode(",",$rndisiplina['dn_observacion']);
                                        $numDatos=count($explode);
                                        for($i=0;$i<$numDatos;$i++){
                                            $consultaObservaciones = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".observaciones WHERE obser_id=$explode[$i] AND obser_id_institucion=".$config['conf_id_institucion']." AND obser_years=".$config['conf_agno']."");
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
	        INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$year}
	        INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=car_docente AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$year}
	        WHERE car_curso='" . $gradoActual . "' AND car_grupo='" . $grupoActual . "'");
            while ($datosCargasDos = mysqli_fetch_array($conCargasDos, MYSQLI_BOTH)) {

                
            ?>
                <tbody>
                    <tr style="color:#000;">
                        <td><?= $datosCargasDos['mat_nombre']; ?><br><span style="color:#C1C1C1;"><?= UsuariosPadre::nombreCompletoDelUsuario($datosCargasDos); ?></span></td>
                        <td>
                        
                            <?php
                            //INDICADORES
                            $indicadores = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga aic
		                    INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON ai.ind_id=aic.ipc_indicador AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$year}
		                    WHERE aic.ipc_carga='" . $datosCargasDos['car_id'] . "' AND aic.ipc_periodo='" . $periodoActual . "' AND aic.institucion={$config['conf_id_institucion']} AND aic.year={$year}");
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