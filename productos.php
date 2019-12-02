<?php 
/*
*	M07.Desenvolupament Web en Entorn Servidor
*	Fecha: 20191129 
*	Autores: Mekwanint Tort, Marcos Abaurrea.
*	URL: http://localhost/tienda/productos.php
*/
session_start(); 
// inicializa variables de sesion
if(!isset($_SESSION['productos'])){
	$_SESSION['productos'] = array();
}
if(!isset($_SESSION['prTotal'])){
	$_SESSION['prTotal'] = 0;
}

class Productos{
	private $fProductos;
	private $usReg;

	function __construct($fProductos,$usReg){
		$this->fProductos = $fProductos;
		$this->usReg = $usReg;
	}

	public function leerProductos(){ // lee los productos desde el archivo
		$ruta_img = "http://localhost/tienda/img/";		
		$f  = fopen($this->fProductos, "r") or die("can't open file");
		$good = true;
		while(!feof($f)){
			$line = fgets($f);
			$arProductos = explode(";",$line);
			echo "<div class=productos>";
			echo "<img class=term_img width=200 height=200 src=".$ruta_img.trim($arProductos[1])." />";
			echo "<span class=datos>".trim($arProductos[0])."</span><span class=datos>Precio: ".trim($arProductos[2])."€</span>";
			echo "<input type=checkbox name='check[]' value='$arProductos[0];$arProductos[2];' />";
			echo "<input type=text name='cant[]' placeholder=Cantidad />";
			echo "</div>";
		}
		fclose($f);
	}

	public function guardarProductos(){// guarda los productos
		$carro = array();
		$canti =array();
		$canTotal = 0;
		if(isset($_POST['submit']) && isset($_POST['check']) && $_REQUEST['cant'] != null){
			foreach($_POST['check'] as $w){
				array_push($carro,$w); // recorre los checks y los almacena en una array							
			}
			foreach($_POST['cant'] as $x){
				array_push($canti,$x);	// recorre las cantidades y las almacena en una array
			}
			$cant2 = array_filter($canti,"strlen"); // elimina los valores vacios
			$cant3 = array_values($cant2); // reasigna los valores de los indices del array
			for($i=0;$i<count($carro);$i++){
				array_push($_SESSION['productos'],$carro[$i].$cant3[$i]); // actualiza la variable sesion de los productos		
			}
			foreach($_SESSION['productos'] as $k){ 
				$pT = explode(";",$k);
				$canTotal += trim($pT[2]);	
			}	
			$_SESSION['prTotal'] = $canTotal; // variable que guarda la cantidad total de productos añadidos
		}
	}
}

if(!isset($_SESSION['user'])){ // si la sesion no esta iniciada va al index.php
	header("location: index.php");
}else{
	$u = $_SESSION['user'];
	$p = new Productos("productos.txt",$u);
	$p->guardarProductos();

?>
<!doctype html>
<html>
	<head>	
		<meta charset="UTF-8">
		<title>Productos</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />
	</head>
	<body>
		<div class="menu-bar">
			<span><i class="fas fa-user-circle"></i> <?php echo $u; ?></span>
			<a href="logout.php" id="logout"> Logout </a>
			<a href="carrito.php" id="carrito"> <i class="fas fa-shopping-cart"></i>
				<div id="contador"><?php 
				if($_SESSION['prTotal'] == 0){
					$canTotal = 0;
				}else{
					$canTotal = $_SESSION['prTotal'];
				}
				echo $canTotal;
				?></div>
        	</a>
		</div>
		<h2>Game Boy</h2>
		<div id="titol">
			<form class="form_productos" method="post" action="">
				<?php $p->leerProductos(); ?>
				<div class="inp">
					<input type="submit" name="submit" value="Añadir al carrito" />
				</div>
			</form>
		</div>
	</body>
</html>
<?php
}
?>