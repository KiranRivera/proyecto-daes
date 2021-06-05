<?php session_start();

// Comprobamos tenga sesion, si no entonces redirigimos y matamos la ejercución de la pagina.
if (isset($_SESSION['usuario'])) {
	require 'views/contenido.view.php';
} else {
	header('Location: login.php');
	die();
}


?>