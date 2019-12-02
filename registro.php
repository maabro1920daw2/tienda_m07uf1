<?php
/*
*	M07.Desenvolupament Web en Entorn Servidor
*	Fecha: 20191129 
*	Autores: Mekwanint Tort, Marcos Abaurrea.
*	URL: http://localhost/tienda/registro.php
*/
class registro {
	private $username; 
	private $password; 

	public function __construct($u,$p){ 
		$this->username = $u;
		$this->password = $p;
	}

	public function validar_registro(){ // funcion para validar el registro
        $data = 'usuarios.txt';
        $fLectura = fopen($data,"r");
        $buscaUsuario = false; 

        while(!feof($fLectura)){ // busca si el usuario esta en el fichero de usuarios
            $line = fgets($fLectura);
            $array = explode(';', $line);
            if($array[0] == $this->username){
                $buscaUsuario = true;
                break;
            }
        }
        fclose($fLectura);
      
        if($buscaUsuario){  
            // error para si el usuario existe     
            echo "<div class=errores><i class='fas fa-times-circle'></i> <span>Este usuario ya existe, prueba con otro.</span></div>";            
    	}else{ 
            if(strlen($this->password)<6){
                // error para comprobar el tamaño de la contraseña
                echo "<div class=errores><i class='fas fa-times-circle'></i> <span>La contraseña debe tener al menos 6 caracteres.</span></div>"; 
            }else{
                // registro en el archivo de usuarios
                $fRegistro = fopen($data,"a");
                fwrite($fRegistro,$this->username.";".$this->password.PHP_EOL);
                fclose($fRegistro);
                header("location: index.php");
            }
        }
    }       
}	
?>
<!doctype html>
<html>
	<head>	
		<meta charset="UTF-8">
		<title>Registro</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />
	</head>
	<body>
        <div class="contenidor">
        <?php 
        if(isset($_POST["registro"])){ 
            $Registro = new registro($_POST["username"],$_POST['password']); 
            $Registro->validar_registro(); 
        }
        ?>
		<form class="formulari" action="" method="post">
            <div class="input-contenidor">
                <input type="email" id="username" name="username" placeholder="Email" required><br/><br/>
            </div>
            <div class="input-contenidor">
			    <input type="password" id="password" name="password" placeholder="Contraseña" required><br/><br/>
            </div>
			<button type="submit" name="registro" class="button">Registro</button>
        </form>
        <p>¿Ya tienes cuenta? <a class="link" href="index.php">Vuelve al login</a></p>
        </div>
	</body>
</html>