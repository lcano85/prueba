<?php

require_once 'connection.php';

session_start();

if(isset($_SESSION["user_login"]))
{
	header("location: welcome.php");
}

if(isset($_REQUEST['btn_login']))
{
	$username	=strip_tags($_REQUEST["txt_username_email"]);
	$email		=strip_tags($_REQUEST["txt_username_email"]);
	$password	=strip_tags($_REQUEST["txt_password"]);
		
	if(empty($username)){						
		$errorMsg[]="please enter username or email";
	}
	else if(empty($email)){
		$errorMsg[]="please enter username or email";
	}
	else if(empty($password)){
		$errorMsg[]="please enter password";
	}
	else
	{
		try
		{
			$select_stmt=$db->prepare("SELECT * FROM tbl_user WHERE username=:uname OR email=:uemail");
			$select_stmt->execute(array(':uname'=>$username, ':uemail'=>$email));
			$row=$select_stmt->fetch(PDO::FETCH_ASSOC);
			
			if($select_stmt->rowCount() > 0)
			{
				if($username==$row["username"] OR $email==$row["email"])
				{
					if(password_verify($password, $row["password"]))
					{
						$_SESSION["user_login"] = $row["user_id"];
						$loginMsg = "Iniciando sesión...";
						header("refresh:2; welcome.php");
					}
					else
					{
						$errorMsg[]="wrong password";
					}
				}
				else
				{
					$errorMsg[]="wrong username or email";
				}
			}
			else
			{
				$errorMsg[]="wrong username or email";
			}
		}
		catch(PDOException $e)
		{
			$e->getMessage();
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
<title>Iniciar Sesión</title>
		
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
					<strong><?php echo $error; ?></strong>
				</div>
            <?php
			}
		}
		if(isset($loginMsg))
		{
		?>
			<div class="alert alert-success">
				<strong><?php echo $loginMsg; ?></strong>
			</div>
        <?php
		}
		?>   
			<center><h2>Iniciar Sesión</h2></center>
			<form method="post" class="form-horizontal">
					
				<div class="form-group">
				<label class="col-sm-3 control-label">Usuario o Email</label>
				<div class="col-sm-6">
				<input type="text" name="txt_username_email" class="form-control" placeholder="Ingresar usuario o email" />
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
				<input type="submit" name="btn_login" class="btn btn-success" value="Ingresar">
				</div>
				</div>
				
				<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9 m-t-15">
					<a href="register.php"><p class="text-info">Registrate</p></a>		
				</div>
				</div>
					
			</form>
			
		</div>
		
	</div>
			
	</div>
										
	</body>
</html>