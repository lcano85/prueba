<?php

require_once "connection.php";

if(isset($_REQUEST['btn_register']))
{
	$username	= strip_tags($_REQUEST['txt_username']);
	$email		= strip_tags($_REQUEST['txt_email']);
	$password	= strip_tags($_REQUEST['txt_password']);
		
	if(empty($username)){
		$errorMsg[]="Ingresar un usuario";
	}
	else if(empty($email)){
		$errorMsg[]="Ingresar un email";
	}
	else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$errorMsg[]="Ingresar un email valido";
	}
	else if(empty($password)){
		$errorMsg[]="Ingresar una clave";
	}
	else if(strlen($password) < 6){
		$errorMsg[] = "La clave debe ser mayor que 06 caracteres";
	}
	else
	{	
		try
		{	
			$select_stmt=$db->prepare("SELECT username, email FROM tbl_user WHERE username=:uname OR email=:uemail");
			
			$select_stmt->execute(array(':uname'=>$username, ':uemail'=>$email));
			$row=$select_stmt->fetch(PDO::FETCH_ASSOC);	
			
			if($row["username"]==$username){
				$errorMsg[]="El usuario ya existe";
			}
			else if($row["email"]==$email){
				$errorMsg[]="El email ya existe";
			}
			else if(!isset($errorMsg))
			{
				$new_password = password_hash($password, PASSWORD_DEFAULT);
				
				$insert_stmt=$db->prepare("INSERT INTO tbl_user	(username,email,password) VALUES
																(:uname,:uemail,:upassword)");				
				
				if($insert_stmt->execute(array(	':uname'	=>$username, 
												':uemail'	=>$email, 
												':upassword'=>$new_password))){
													
					$registerMsg="Se registro correctamente... Por favor, hacer click en Iniciar Sesión";
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
<title>Registrate</title>
		
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<script src="js/jquery-1.12.4-jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
		
</head>

	<body>
	
	<div class="wrapper">
	
	<div class="container">
			
		<div class="col-lg-12">
		
		<?php
		if(isset($errorMsg))
		{
			foreach($errorMsg as $error)
			{
			?>
				<div class="alert alert-danger">
					<strong>Errores: <?php echo $error; ?></strong>
				</div>
            <?php
			}
		}
		if(isset($registerMsg))
		{
		?>
			<div class="alert alert-success">
				<strong><?php echo $registerMsg; ?></strong>
			</div>
        <?php
		}
		?>   
			<center><h2>Registrate</h2></center>
			<form method="post" class="form-horizontal">
					
				
				<div class="form-group">
				<label class="col-sm-3 control-label">Usuario</label>
				<div class="col-sm-6">
				<input type="text" name="txt_username" class="form-control" placeholder="Ingresar usuario" />
				</div>
				</div>
				
				<div class="form-group">
				<label class="col-sm-3 control-label">Email</label>
				<div class="col-sm-6">
				<input type="text" name="txt_email" class="form-control" placeholder="Ingresar email" />
				</div>
				</div>
					
				<div class="form-group">
				<label class="col-sm-3 control-label">Clave</label>
				<div class="col-sm-6">
				<input type="password" name="txt_password" class="form-control" placeholder="Ingresar clave" />
				</div>
				</div>
					
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
				<input type="submit"  name="btn_register" class="btn btn-primary " value="Registrate">
				</div>
				</div>
				
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
					<a href="index.php"><p class="text-info">Ingresar sesión</p></a>		
				</div>
				</div>
					
			</form>
			
		</div>
		
	</div>
			
	</div>
										
	</body>
</html>