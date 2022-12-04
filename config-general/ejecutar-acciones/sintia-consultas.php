<?php
$conexion = mysql_connect("localhost","mobiliar","M1X32znd9l");
$e=0;

//AGREGAR/MODIFICAR COLUMNAS
$inicio = "ALTER TABLE";
$tabla = "matriculas_aspectos"; 
$columnas = "
ADD COLUMN `mata_aprobacion_acudiente` INTEGER UNSIGNED DEFAULT 0 AFTER `mata_periodo`,
 ADD COLUMN `mata_aprobacion_acudiente_fecha` DATETIME AFTER `mata_aprobacion_acudiente`
";

/* SIN EJECUTAR
ALTER TABLE `mobiliar_icolven_2022`.`matriculas_aspectos` ADD COLUMN `mata_aprobacion_acudiente` INTEGER UNSIGNED DEFAULT 0 AFTER `mata_periodo`,
 ADD COLUMN `mata_aprobacion_acudiente_fecha` DATETIME AFTER `mata_aprobacion_acudiente`;

ALTER TABLE `sintia_2020`.`academico_materias` ADD COLUMN `mat_valor` VARCHAR(45) COMMENT 'Valor porcentual dentro de cada area' AFTER `mat_portada`;


ALTER TABLE `mobiliar_icolven_2021`.`matriculas_aspectos` ADD COLUMN `mata_fecha_evento` DATE AFTER `mata_fecha`,
 ADD COLUMN `mata_aspectos_positivos` LONGTEXT AFTER `mata_fecha_evento`,
 ADD COLUMN `mata_aspectos_mejorar` LONGTEXT AFTER `mata_aspectos_positivos`,
 ADD COLUMN `mata_tratamiento` LONGTEXT AFTER `mata_aspectos_mejorar`,
 ADD COLUMN `mata_descripcion` LONGTEXT AFTER `mata_tratamiento`;


 ALTER TABLE `mobiliar_icolven_2021`.`matriculas_aspectos` ADD COLUMN `mata_periodo` INTEGER UNSIGNED AFTER `mata_descripcion`;

*/

#EOA CIRUELOS
mysql_select_db("mobiliar_eoa_ciruelos_2022",$conexion);
mysql_query($inicio." mobiliar_eoa_ciruelos_2022.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - mobiliar_eoa_ciruelos_2022<br>"; $e=1;}


#INNOVADORES
mysql_select_db("mobiliar_innovadores_2022",$conexion);
mysql_query($inicio." mobiliar_innovadores_2022.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - mobiliar_innovadores_2022<br>"; $e=1;}

#REDENCIÓN
mysql_select_db("mobiliar_redencion_2021",$conexion);
mysql_query($inicio." mobiliar_redencion_2021.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - mobiliar_redencion_2021<br>"; $e=1;}

mysql_select_db("mobiliar_redencion_2022",$conexion);
mysql_query($inicio." mobiliar_redencion_2022.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - mobiliar_redencion_2022<br>"; $e=1;}

#JOSE ANTONIO GALÁN
mysql_select_db("mobiliar_jose_antonio_2021",$conexion);
mysql_query($inicio." mobiliar_jose_antonio_2021.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - mobiliar_jose_antonio_2021<br>"; $e=1;}

#ELLEN KEY
mysql_select_db("mobiliar_ellenkey_2020",$conexion);
mysql_query($inicio." mobiliar_ellenkey_2020.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - mobiliar_ellenkey_2020<br>"; $e=1;}

mysql_select_db("mobiliar_ellenkey_2021",$conexion);
mysql_query($inicio." mobiliar_ellenkey_2021.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - mobiliar_ellenkey_2021<br>"; $e=1;}

mysql_select_db("mobiliar_ellenkey_2022",$conexion);
mysql_query($inicio." mobiliar_ellenkey_2022.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - mobiliar_ellenkey_2022<br>"; $e=1;}

#E.O ALTOS ORIENTE
mysql_select_db("mobiliar_eoa_altosoriente_2020",$conexion);
mysql_query($inicio." mobiliar_eoa_altosoriente_2020.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eoa_altosoriente_2020<br>"; $e=1;}


#E.O LA CAMILA
mysql_select_db("mobiliar_eoa_lacamila_2020",$conexion);
mysql_query($inicio." mobiliar_eoa_lacamila_2020.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eoa_lacamila_2020<br>"; $e=1;}

mysql_select_db("mobiliar_eoa_lacamila_2021",$conexion);
mysql_query($inicio." mobiliar_eoa_lacamila_2021.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eoa_lacamila_2021<br>"; $e=1;}

mysql_select_db("mobiliar_eoa_lacamila_2022",$conexion);
mysql_query($inicio." mobiliar_eoa_lacamila_2022.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eoa_lacamila_2022<br>"; $e=1;}


#E.O PARIS
mysql_select_db("mobiliar_eoa_paris_2020",$conexion);
mysql_query($inicio." mobiliar_eoa_paris_2020.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eoa_paris_2020<br>"; $e=1;}

mysql_select_db("mobiliar_eoa_paris_2021",$conexion);
mysql_query($inicio." mobiliar_eoa_paris_2021.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eoa_paris_2021<br>"; $e=1;}


#E.O EL PINAR
mysql_select_db("mobiliar_eoa_pinar_2020",$conexion);
mysql_query($inicio." mobiliar_eoa_pinar_2020.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eoa_pinar_2020<br>"; $e=1;}

mysql_select_db("mobiliar_eoa_pinar_2021",$conexion);
mysql_query($inicio." mobiliar_eoa_pinar_2021.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eoa_pinar_2021<br>"; $e=1;}


#E.O SANTA RITA
mysql_select_db("mobiliar_eoa_srita_2020",$conexion);
mysql_query($inicio." mobiliar_eoa_srita_2020.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eoa_srita_2020<br>"; $e=1;}

mysql_select_db("mobiliar_eoa_srita_2021",$conexion);
mysql_query($inicio." mobiliar_eoa_srita_2021.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eoa_srita_2021<br>"; $e=1;}


#MAXTRUMMER
mysql_select_db("mobiliar_maxtrummer_2019",$conexion);
mysql_query($inicio." mobiliar_maxtrummer_2019.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - maxtrummer_2019<br>"; $e=1;}

mysql_select_db("mobiliar_maxtrummer_2020",$conexion);
mysql_query($inicio." mobiliar_maxtrummer_2020.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - maxtrummer_2020<br>"; $e=1;}

mysql_select_db("mobiliar_maxtrummer_2021",$conexion);
mysql_query($inicio." mobiliar_maxtrummer_2021.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - maxtrummer_2021<br>"; $e=1;}

mysql_select_db("mobiliar_maxtrummer_2022",$conexion);
mysql_query($inicio." mobiliar_maxtrummer_2022.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - maxtrummer_2022<br>"; $e=1;}


#COALST
mysql_select_db("mobiliar_coalst_2018",$conexion);
mysql_query($inicio." mobiliar_coalst_2018.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - coalst_2018<br>"; $e=1;}

mysql_select_db("mobiliar_coalst_2019",$conexion);
mysql_query($inicio." mobiliar_coalst_2019.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - coalst_2019<br>"; $e=1;}

mysql_select_db("mobiliar_coalst_2020",$conexion);
mysql_query($inicio." mobiliar_coalst_2020.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - coalst_2020<br>"; $e=1;}

mysql_select_db("mobiliar_coalst_2021",$conexion);
mysql_query($inicio." mobiliar_coalst_2021.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - coalst_2021<br>"; $e=1;}

mysql_select_db("mobiliar_coalst_2022",$conexion);
mysql_query($inicio." mobiliar_coalst_2022.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - coalst_2022<br>"; $e=1;}


#EDUARDO ORTEGA
mysql_select_db("mobiliar_eduardoortega_2015",$conexion);
mysql_query($inicio." mobiliar_eduardoortega_2015.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eduardoortega_2015<br>"; $e=1;}

mysql_select_db("mobiliar_eduardoortega_2016",$conexion);
mysql_query($inicio." mobiliar_eduardoortega_2016.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eduardoortega_2016<br>"; $e=1;}

mysql_select_db("mobiliar_eduardoortega_2017",$conexion);
mysql_query($inicio." mobiliar_eduardoortega_2017.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eduardoortega_2017<br>"; $e=1;}

mysql_select_db("mobiliar_eduardoortega_2018",$conexion);
mysql_query($inicio." mobiliar_eduardoortega_2018.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eduardoortega_2018<br>"; $e=1;}

mysql_select_db("mobiliar_eduardoortega_2019",$conexion);
mysql_query($inicio." mobiliar_eduardoortega_2019.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eduardoortega_2019<br>"; $e=1;}

mysql_select_db("mobiliar_eduardoortega_2020",$conexion);
mysql_query($inicio." mobiliar_eduardoortega_2020.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eduardoortega_2020<br>"; $e=1;}

mysql_select_db("mobiliar_eduardoortega_2021",$conexion);
mysql_query($inicio." mobiliar_eduardoortega_2021.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - eduardoortega_2021<br>"; $e=1;}



#ICOLVEN
mysql_select_db("mobiliar_icolven_2016",$conexion);
mysql_query($inicio." mobiliar_icolven_2016.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - icolven_2016<br>"; $e=1;}

mysql_select_db("mobiliar_icolven_2017",$conexion);
mysql_query($inicio." mobiliar_icolven_2017.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - icolven_2017<br>"; $e=1;}

mysql_select_db("mobiliar_icolven_2018",$conexion);
mysql_query($inicio." mobiliar_icolven_2018.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - icolven_2018<br>"; $e=1;}

mysql_select_db("mobiliar_icolven_2019",$conexion);
mysql_query($inicio." mobiliar_icolven_2019.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - icolven_2019<br>"; $e=1;}

mysql_select_db("mobiliar_icolven_2020",$conexion);
mysql_query($inicio." mobiliar_icolven_2020.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - icolven_2020<br>"; $e=1;}

mysql_select_db("mobiliar_icolven_2021",$conexion);
mysql_query($inicio." mobiliar_icolven_2021.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - icolven_2021<br>"; $e=1;}

mysql_select_db("mobiliar_icolven_2022",$conexion);
mysql_query($inicio." mobiliar_icolven_2022.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - icolven_2022<br>"; $e=1;}


#DEMO
mysql_select_db("mobiliar_sintiademo",$conexion);
mysql_query($inicio." mobiliar_sintiademo.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - DEMO<br>"; $e=1;}

/*RETIRADOS
#IC&T
mysql_select_db("mobiliar_ict_2019",$conexion);
mysql_query($inicio." mobiliar_ict_2019.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - ict_2019<br>"; $e=1;}
mysql_query($inicio." mobiliar_ict_2020.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - ict_2020<br>"; $e=1;}

#CEMPED
mysql_select_db("mobiliar_cemped_2016",$conexion);
mysql_query($inicio." mobiliar_cemped_2016.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - cemped_2016<br>"; $e=1;}

mysql_select_db("mobiliar_cemped_2017",$conexion);
mysql_query($inicio." mobiliar_cemped_2017.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - cemped_2017<br>"; $e=1;}

mysql_select_db("mobiliar_cemped_2018",$conexion);
mysql_query($inicio." mobiliar_cemped_2018.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - cemped_2018<br>"; $e=1;}

mysql_select_db("mobiliar_cemped_2019",$conexion);
mysql_query($inicio." mobiliar_cemped_2019.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - cemped_2019<br>"; $e=1;}

mysql_select_db("mobiliar_cemped_2020",$conexion);
mysql_query($inicio." mobiliar_cemped_2020.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - cemped_2020<br>"; $e=1;}

mysql_query($inicio." mobiliar_avc_2019.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - avc_2019<br>"; $e=1;}

mysql_query($inicio." mobiliar_instival_2017.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - instival_2017<br>"; $e=1;}
mysql_query($inicio." mobiliar_instival_2018.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - instival_2018<br>"; $e=1;}
mysql_query($inicio." mobiliar_instival_2019.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - instival_2019<br>"; $e=1;}
mysql_query($inicio." mobiliar_instival_2020.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - instival_2020<br>"; $e=1;}

mysql_query($inicio." mobiliar_iecelco_2016.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - iecelco_2016<br>"; $e=1;}
mysql_query($inicio." mobiliar_iecelco_2017.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - iecelco_2017<br>"; $e=1;}
mysql_query($inicio." mobiliar_iecelco_2018.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - iecelco_2018<br>"; $e=1;}
mysql_query($inicio." mobiliar_iecelco_2019.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - iecelco_2019<br>"; $e=1;}

mysql_query($inicio." mobiliar_cads_2015.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - cads_2015<br>";}
mysql_query($inicio." mobiliar_cads_2016.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - cads_2016<br>";}
mysql_query($inicio." mobiliar_cads_2017.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - cads_2017<br>";}
mysql_query($inicio." mobiliar_cads_2018.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - cads_2018<br>";}

mysql_query($inicio." mobiliar_simonbolivar_2016.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - simonbolivar_2016<br>";}
mysql_query($inicio." mobiliar_simonbolivar_2017.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - simonbolivar_2017<br>";}

mysql_query($inicio." mobiliar_caq_2016.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - caq_2016<br>";}
mysql_query($inicio." mobiliar_caq_2017.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - caq_2017<br>";}
mysql_query($inicio." mobiliar_caq_2018.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - caq_2018<br>";}
mysql_query($inicio." mobiliar_caq_2019.".$tabla." ".$columnas,$conexion);
if(mysql_errno()!=0){echo mysql_error()." - caq_2019<br>";}
*/
if($e=='0'){
	echo "<b>La consulta fue ejecutada correctamente para todas las Instituciones</b> - Tabla: ".$tabla.", Columnas: ".$columnas;
}
?>