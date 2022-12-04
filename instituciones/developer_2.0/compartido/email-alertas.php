<?php
						$fin =  '<html><body>';
						$fin .= '
						<table width="80%" align="center" border="1" style="font-family:Verdana, Arial, Helvetica, sans-serif;" rules="groups" cellpadding="3" cellspacing="3">
					
					<tr>
						<td style="background:#1fbba6; color:#FFFFFF; text-align:center;" colspan="2">
							<h1>SINTIA&reg; - CONTRIBUYE A LA EXCELENCIA EDUCATIVA</h1>
						</td>
					</tr>
					
					<tr>
						<td style="background:#ffd300; color:#FFFFFF; text-align:right;">FECHA</td>
						<td style="background:#F6F6F6; color:#000000; text-align:left;">&nbsp;'.date("d/M/Y").'</td>
					</tr>
					
					<tr>
						<td style="background:#ffd300; color:#FFFFFF; text-align:right;">TIPO DE ALERTA</td>
						<td style="background:#F6F6F6; color:#000000; text-align:left;">&nbsp;'.$alertaTipo.'</td>
					</tr>
					
					<tr>
						<td style="background:#ffd300; color:#FFFFFF; text-align:right;">ESTUDIANTE</td>
						<td style="background:#F6F6F6; color:#000000; text-align:left;">&nbsp;'.$alertaEstudiante.'</td>
					</tr>
					
					<tr>
						<td style="background:#ffd300; color:#FFFFFF; text-align:right;">ASIGNATURA</td>
						<td style="background:#F6F6F6; color:#000000; text-align:left;">&nbsp;'.$alertaAsignatura.'</td>
					</tr>
					
					<tr>
						<td style="background:#FFFFFF; color:#000000; text-align:center; font-size:10px;" colspan="2">
							<span style="font-size:18px;">SINTIA&reg; - CONTRIBUYE A LA EXCELENCIA EDUCATIVA</span><br>
							info@plataformasintia.com<br>
							(4) 585 3755 - 318 347 9394
						</td>
					</tr>
					
				</table>
						';
						
						
						
						$fin .='';
							
						$fin .=  '<html><body>';
						
				
						$sfrom="alertas@plataformasintia.com"; //LA CUETA DEL QUE ENVIA EL MENSAJE
				
						$sdestinatario="jomejia@unac.edu.co,".$_POST["email"]; //CUENTA DEL QUE RECIBE EL MENSAJE
				
						$ssubject="Plataforma SINTIA"; //ASUNTO DEL MENSAJE 
				
						$shtml=$fin; //MENSAJE EN SI
				
						$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 
				
						$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 
				
						$sheader=$sheader."Mime-Version: 1.0\n"; 
				
						$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 
				
						@mail($sdestinatario,$ssubject,$shtml,$sheader);
						//header("Location:index.html");
						//exit();
				?>