<?php
	$Servidor    =  'localhost';
	$Usuario     =  'root';
	$Password    =  'jonajona';
	$BaseDeDatos =  'pixeland_posres';			
		

	
	$conexio = mysql_connect($Servidor, $Usuario, $Password ) or die (mysql_error()); //conexion respaldo
	mysql_select_db($BaseDeDatos, $conexio ) or die (mysql_error());
		

	//$archivo="../../../../01_RESPALDOS/respal_pixeland_posres".".sql";
		$archivo="c:/01_RESPALDOS/respal_pixeland_posres".".sql";
 
		$sistema="show variables where variable_name= 'basedir'";
		$rs_sistema=mysql_query($sistema);
		$DirBase=mysql_result($rs_sistema,0,"value");
		$primero=substr($DirBase,0,1);
		if ($primero=="/") {
					$DirBase="mysqldump";
		} else {
					$DirBase=$DirBase."\bin\mysqldump";
		}
	
		$executa = "$DirBase -h $Servidor -u $Usuario --password=$Password --opt --ignore-table=$BaseDeDatos.backup_mstr $BaseDeDatos > $archivo";
     
		system($executa, $resultado); 


		if ($resultado) { $errors [] = "Error ejecutando comando: $executa"; } 
		else echo "Se creo BD de respaldo <br>";

		
	
		
if (isset($errors)){
			
			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> 
					<?php
						foreach ($errors as $error) {
								echo $error;
							}
						?>
			</div>
			<?php
			}
			if (isset($messages)){
				
				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
							?>
				</div>
				<?php
			}		
						
?>

