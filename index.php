<?php
session_start();
// Si ya tiene sesion iniciada no tiene sentido volver a mostrar el login, por lo tanto se redirige al usuario a otra pagina
if (isset($_SESSION['rol']))
{
	// Si es admin, redirigir a mensajes.php
	if ($_SESSION['rol'] == 'admin')
	{
		header('Location: mensajes.php');
    exit();
	}
	// Si es un usuario normal, redirigir a equipos.php
	else if ($_SESSION['rol'] == 'usuario')
	{
		header('Location: equipos.php');
    exit();
	}
}

// Si se lleno el formulario de login
if (isset($_POST['username']))
{
	include_once "utils.php";
	include_once "db.php";
	// Limpiar la entrada para prevenir SQL injection
	$username = limpiar_entrada($_POST['username']);
	$password = limpiar_entrada($_POST['password']);

	// llamar la funcion login() de db.php, encargada de verificar en la db si existe el usuario
	$user = login($username, $password);

	// Si inicio sesion satisfactoriamente, hacemos un set de las variables de sesion
	if ($user)
	{
		// Set session variables
		$_SESSION['usuario'] = $user['usuario'];
		$_SESSION['email'] = $user['email'];
		$_SESSION['rol'] = $user['rol'];

		// Luego de iniciar sesion redirigir a la pagina "mensajes.php" si es admin o a la pagina "equipos.php" si es un usuario normal
		if ($_SESSION['rol'] == 'admin')
		{
			header('Location: mensajes.php');
		}
		else if ($_SESSION['rol'] == 'usuario')
		{
			header('Location: equipos.php');
		}
    exit();
	}
	else
	{
		echo "Usuario o contraseña incorrectos"; // TODO: mover a un mensaje de error en el form
	}

}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="topnav">
  <a class="active" href="index.php">Inicio</a>
  <a href="eventos.php">Eventos</a>
  <a href="salas.php">Salas</a>
  <a href="equipos.php">Equipos</a>
  <a href="libros.php">Libros</a>
	<?php

	?>
</div>

<div style="padding-left:16px">
	<br>
	<br>
   	<div class="w3-card-4">
	  <div class="w3-container w3-green">
	    <h2>Ingrese a la plataforma</h2>
	  </div>
	  <form method="post" class="w3-container" action="index.php">
	    <p>      
	    <label class="w3-text-green"><b>Usuario</b></label>
	    <input class="w3-input w3-border w3-sand" name="username" type="text" required></p>
	    <p>      
	    <label class="w3-text-green"><b>Contraseña</b></label>
	    <input class="w3-input w3-border w3-sand" name="password" type="password" required></p>
	    <p>
	    <button class="w3-btn w3-green" type="submit">Ingreso</button>
	    <a href="Registro.php"> Usuario Nuevo? Registrese</a>
	    </p>
	  </form>
   </div>
</div>

</body>
</html>