<?php

    if($_REQUEST["nume"] == "" ){$_REQUEST["nume"] = "1";}
    $consulta=mysqli_query($conexion,"SELECT * FROM academico_cargas
    INNER JOIN academico_grados ON gra_id=car_curso
    INNER JOIN academico_grupos ON gru_id=car_grupo
    INNER JOIN academico_materias ON mat_id=car_materia
    INNER JOIN usuarios ON uss_id=car_docente
    WHERE car_id=car_id 
    ORDER BY car_id;");
    $num_registros=mysqli_num_rows($consulta);
    $registros= 5;
    $pagina=$_REQUEST["nume"];
    $contReg = 1;
    ?>