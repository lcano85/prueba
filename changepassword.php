<?php

require_once "connection.php";

session_start();

if(!isset($_SESSION['user_login']))
{
	header("location: index.php");
}

$id = $_SESSION['user_login'];

if(isset($_REQUEST['btn_actualizar']))
{
	$clave_actual	= strip_tags($_REQUEST['txt_clave_actual']);
	$clave_nueva	= strip_tags($_REQUEST['txt_clave_nueva']);
	$clave_confirma	= strip_tags($_REQUEST['txt_clave_confirma']);
		
	if(empty($clave_actual))
	{
		$errorMsg[]="Ingresar una clave";
	}
	else if(strlen($clave_actual) < 8)
	{
		$errorMsg[] = "La clave debe ser mayor que 06 caracteres";
	}
	else
	{	
		try
		{	
			$select_stmt=$db->prepare("SELECT username, password, email FROM tbl_user WHERE user_id=:uid");
			$select_stmt->execute(array(":uid"=>$id));
			$row=$select_stmt->fetch(PDO::FETCH_ASSOC);	
			
			if($row["password"]==$clave_actual){
				$errorMsg[]="La clave actual no es la misma";
			}
			else if($row["password"]==$clave_actual)
			{
				$new_password = password_hash($clave_nueva, PASSWORD_DEFAULT);
				
				$insert_stmt=$db->prepare("UPDATE tbl_user	(password) VALUES
																(:upassword)");				
				
				if($insert_stmt->execute(array(	':upassword'=>$new_password))){
													
					$registerMsg="Se actualizo la clave correctamente";
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
<title>Bienvenido</title>
		
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<script src="js/jquery-1.12.4-jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
		
</head>

	<body>
	
	<div class="wrapper">
	<div class="container">
			
		<div class="col-lg-12">
			<center>
				<h2>
				<?php
				
				require_once 'connection.php';
				
				session_start();

				if(!isset($_SESSION['user_login']))
				{
					header("location: index.php");
				}
				
				$id = $_SESSION['user_login'];
				
				$select_stmt = $db->prepare("SELECT * FROM tbl_user WHERE user_id=:uid");
				$select_stmt->execute(array(":uid"=>$id));
	
				$row=$select_stmt->fetch(PDO::FETCH_ASSOC);
				
				if(isset($_SESSION['user_login']))
				{
				?>
					Bienvenido,
				<?php
						echo $row['username'];
				}
				?>
				</h2>
					
			</center>
			<form method="post" class="form-horizontal">
					
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Clave actual</label>
					<div class="col-sm-6">
						<input type="text" name="txt_clave_actual" class="form-control" placeholder="Ingresar clave actual" />
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Nueva clave</label>
					<div class="col-sm-6">
						<input type="text" name="txt_clave_nueva" class="form-control" placeholder="Ingresar nueva clave" />
					</div>
				</div>
					
				<div class="form-group">
					<label class="col-sm-3 control-label">Confirmar clave</label>
					<div class="col-sm-6">
						<input type="password" name="txt_clave_confirma" class="form-control" placeholder="Ingresar confirmar clave" />
					</div>
				</div>
					
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9 m-t-15">
						<input type="submit"  name="btn_actualizar" class="btn btn-primary " value="Actualizar">
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9 m-t-15">
						<a href="index.php"><p class="text-info">Opciones</p></a>		
					</div>
				</div>
					
			</form>
		</div>
		
	</div>	
	</div>
										
	</body>
</html>