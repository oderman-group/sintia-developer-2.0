<?php
$conexion = mysqli_connect('sintia.co', 'mobiliar_testing', 'R#gm;UP(=ur6', 'mobiliar_sintia_admin');
mysqli_query($conexion, "INSERT INTO sys_jobs(job_estado, job_tipo, job_fecha_creacion, job_fecha_modificacion, job_responsable, job_id_institucion, job_year, job_parametros, job_mensaje, job_intentos, job_prioridad)VALUES('PRUEBA','JOBS PRUEBA',now(),now(),3349,22,'2023','{carga:481,periodo:2,grado:5,grupo:3}','Prueba para ver si funciona el JOBS',1,'Media')");