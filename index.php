<?php
	// Emanuel De Jesús Rivera Ruiz
	# 4AVPR
	# 15 de mayo del 2021
?>

<?php session_start();

if(isset($_SESSION['usuario'])) {
	header('Location: contenido.php');
	die();
} else {

	header('Location: registrate.php');
}

?>