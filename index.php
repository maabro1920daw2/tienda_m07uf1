<?php
/*
*	M07.Desenvolupament Web en Entorn Servidor
*	Fecha: 20191129 
*	Autores: Mekwanint Tort, Marcos Abaurrea.
*	URL: http://localhost/tienda/index.php
*/
session_start();

class login {
	private $username; // declaramos usuario
	private $password; // declaramos contraseña

	public function __construct($u,$p){ // el constructor de la clase
		$this->username=$u;
		$this->password=$p;
	}

	public function validar(){ // funcion que valida el usuario y contraseña
		$d='usuarios.txt'; // carga el archivo
		$f=fopen($d,"r"); // lo abre en lectura
    	$good=false; // bandera
   		while(!feof($f)){ // lee hasta el final del archivo
       		$line = fgets($f); // recoge las lineas
        	$array = explode(";",$line); // crea un array con los datos [0] para el usuario y [1] para la contraseña
    		if(trim($array[0]) == $this->username && trim($array[1]) == $this->password){ // comprueba que los datos coincidan, trim() quitar los espacios del array
            	$good=true; // cambio el estado de la bandera al coincidir
            	break; // sale del bucle
        	}
		}
		if($good){ // comprueba la bandera, si es true inicia sesion
			$_SESSION['user'] = $this->username;
			header("location: productos.php");
    	}else{ // si es false muestra el mensaje
			echo "<div class=errores><i class='fas fa-times-circle'></i> <span>Usuario o contraseña incorrectos.</span></div>";
			$ban = true;
    	}
		fclose($f); // cierro el archivo de texto
	}
}
?>
<!doctype html>
<html>
	<head>	
		<meta charset="UTF-8">
		<title>Login</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />
	</head>
	<body>
		<div class="contenidor">
		<?php 
		if(isset($_POST['login'])) { // al hacer clic en el submit
			$log=new login($_POST["username"],$_POST['password']); // crea la nueva clase con los datos
			$log->validar(); // funcion para validar los datos
		}
		?>
		<form class="formulari" action="" method="post">
			<div class="input-contenidor">
				<input type="email" id="username" name="username" placeholder="Email" required><br/><br/>
			</div>
			<div class="input-contenidor">
				<input type="password" id="password" name="password" placeholder="Contraseña" required><br/><br/>
			</div>
			<button type="submit" name="login" class="button">Login</button>
		</form>
		<p>¿Aún no tienes cuenta? <a class="link" href="registro.php">Resgistrate</a></p>
		</div>
	</body>
</html>