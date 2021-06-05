<?php session_start();


// Este codigo funciona como un punto de acceso, al que solo puedes entrar teniendo un usuario y contraseña, tambien te ayuda a crear un usuario y contraseña para luego redirigirte al punto de acceso donde tendras que ingresar tus datos.


// Comprobamos si ya tiene una sesion
# Si ya tiene una sesion redirigimos al contenido, para que no pueda volver a registrar un usuario.
if (isset($_SESSION['usuario'])) {
	header('Location: index.php');
	die();
}

// Comprobamos si ya han sido enviado los datos
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Validamos que los datos hayan sido rellenados
	$usuario = str_replace(' ','' , filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING));
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
    $direccion = $_POST['direccion'];

	$errores = '';

	// Comprobamos que ninguno de los campos este vacio.
	if (empty($usuario) or empty($password) or empty($password2)) {
		$errores = '<li>Por favor rellena todos los datos correctamente</li>';
	} else {

		//se comprueba que no exista otro usuario igual.
		try {
			$conexion = new PDO('mysql:host=localhost;dbname=login_practica', 'root', '');
		} catch (PDOException $e) {
			echo "Error:" . $e->getMessage();
		}

		$statement = $conexion->prepare('SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1');
		$statement->execute(array(':usuario' => $usuario));

		// El metodo fetch nos va a devolver el resultado o false en caso de que no haya resultado.
		$resultado = $statement->fetch();

		// Si resultado es diferente a false entonces significa que ya existe el usuario.
		if ($resultado != false) {
			$errores .= '<li>El nombre de usuario ya existe</li>';
		}

		// Hasheamos nuestra contraseña para protegerla un poco.
		#La contraseña se encripta, esto no asegura que no accedan a tu cuenta pero te da cierta seguridad.
		$password = hash('sha512', $password);
		$password2 = hash('sha512', $password2);

		// Comprueban si las contraseñas coinciden.
		if ($password != $password2) {
			$errores.= '<li>Las contraseñas no son iguales</li>';
		}
	}

	// se comprueban los errores si no hay ninguno de ellos se crea tu usuario.
	if ($errores == '') {
		$statement = $conexion->prepare('INSERT INTO usuarios (id, usuario, pass) VALUES (null, :usuario, :pass)');
		$statement->execute(array(
				':usuario' => $usuario,
				':pass' => $password
			));

		// Despues de registrar al usuario redirigimos para que inicie sesion.
		header('Location: login.php');
	}

	//Mendez Lizo Adel Fernando 4AVP
}
    require 'views/registrate.view.php';
?>