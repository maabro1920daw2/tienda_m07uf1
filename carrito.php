<?php 
/*
*	M07.Desenvolupament Web en Entorn Servidor
*	Fecha: 20191129 
*	Autores: Mekwanint Tort, Marcos Abaurrea.
*	URL: http://localhost/tienda/carrito.php
*/
session_start(); 
if(isset($_POST['borrar'])){
	$_SESSION['productos'] = array();
	$_SESSION['prTotal'] = 0;
}
class Compra{
	private $arProd;
	private $usReg;

	function __construct($arProd,$usReg){
		$this->arProd = $arProd;
		$this->usReg = $usReg;
	}

	private function generarFactura($can,$pre){
		date_default_timezone_set('Europe/Madrid');
		setlocale(LC_TIME, 'spanish');
		$fecha = strftime("%d de %B del %Y");
		$nombre = strstr($this->usReg, "@", true);
		$hora = date('H:i:s');
		$fichero = $nombre."_recibo.txt";
		$f = fopen($fichero,"w");
		fwrite($f,"Factura".PHP_EOL."Nombre: ".ucfirst($nombre).PHP_EOL."Email: ".$this->usReg.PHP_EOL."Fecha: ".$fecha.PHP_EOL."Hora: ".$hora.PHP_EOL);
		fwrite($f,"\n\n");
		foreach($this->arProd as $m){
			$pr = explode(";",$m);
			fwrite($f,"Articulo: ".trim($pr[0])."\nCoste: ".(trim($pr[1])*trim($pr[2]))."€\nCantidad: ".trim($pr[2]).PHP_EOL);
			fwrite($f,"----------------------------------------------------------------------------------------------------".PHP_EOL);	
		}
		fwrite($f,"Cantidad total: ".$can."	Importe total: ".$pre."€");
		fclose($f);
	}

	public function listaCarro(){	
		$precioTotal = 0;
		$canTotal = 0;
		echo "<div class=contenidor>";
		echo "<div class=cn_tabla>";
		echo "<table id=taula_carrito>";
		$arr = $this->arProd;
		echo "<tr><th>Producto</th><th>Precio</th><th>Cantidad</th></tr>";

		foreach($this->arProd as $n){
			$prod = explode(";",$n);
			echo "<tr>
					<td>".trim($prod[0])."</td>
					<td>".trim($prod[1])*trim($prod[2])."€</td>
					<td>".trim($prod[2])."</td>					
				</tr>";
			$precioTotal += (trim($prod[2])*trim($prod[1]));
			$canTotal += trim($prod[2]);
		}
		echo "</table>";
		echo "<p><b>Cantidad Total:</b> ".$canTotal."</p>";
		echo "<p><b>Importe Total:</b> ".$precioTotal."€</p>";
		echo "</div>";
		echo "</div>";
		if(isset($_POST['imprimir'])){
			$this->generarFactura($canTotal,$precioTotal);
		}
	}
}

if(!isset($_SESSION['user'])){ // si la sesion no esta iniciada va al index.php
	header("location: index.php");
}else{
	$u = $_SESSION['user']; // guarda la variable sesion con el nombre del usuario
	$pr = $_SESSION['productos']; // guarda la variable sesion con el array de productos
	$c = new Compra($pr,$u);

	if(isset($_POST['atras'])){
		header("location: productos.php");
	}
?>
<!doctype html>
<html>
	<head>	
		<meta charset="UTF-8">
		<title>Cesta</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />
	</head>
	<body>
		<div class="menu-bar">
			<span><i class="fas fa-user-circle"></i> <?php echo $u; ?></span>
			<a href="logout.php" id="logout"> Logout </a>
		</div>
		<h2>Cesta de la compra</h2>		
		<?php $c->listaCarro(); ?>
		<form method="post" action="">
			<div class="inp2">
				<input type="submit" name="atras" value="Ir atras" />
				<input type="submit" name="imprimir" value="Imprimir en txt" />
				<input type="submit" name="borrar" value="Borrar carro" />
			</div>
		</form>
	</body>
</html>
<?php 
}
?>