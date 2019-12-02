<?php 
session_start();
unset($_SESSION['user']);
unset($_SESSION['productos']);
unset($_SESSION['prTotal']);
session_destroy();
header("Location: index.php");
?>