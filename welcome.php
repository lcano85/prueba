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
				<br><br>
				Opciones:
				<br><br>
					<a href="changepassword.php">Cambiar clave</a>
					<br><br>
					<a href="logout.php">Cerrar sesión</a>
			</center>
			
		</div>
		
	</div>	
	</div>
										
	</body>
</html>