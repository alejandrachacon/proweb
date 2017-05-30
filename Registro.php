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

// Si se lleno el formulario de resgitro
if (isset($_POST['username']))
{
  include_once dirname(__FILE__) . "/utils.php";
  include_once dirname(__FILE__) . "/db.php";
  // Limpiar la entrada para prevenir SQL injection
  $username = limpiar_entrada($_POST['username']);
  $password = limpiar_entrada($_POST['password']);
  $email = limpiar_entrada($_POST['email']);

  // llamar la funcion login() de db.php, encargada de verificar en la db si existe el usuario
  $user = login($username, $password);

  // Si inicio sesion satisfactoriamente, hacemos un set de las variables de sesion
  if ($user)
  {
      echo "<br> <h1> Usuario ya existente </h1>";
  }
  else
  {

    $user = register($username,$password,$email);

    echo "<h1>Prueba ".$user['usuario']."</h1>";
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

}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
body {margin:0;}

.topnav {
  overflow: hidden;
  background-color: #333;
}

.topnav a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
    background-color: #4CAF50;
    color: white;
}
</style>
</head>
<body>

<div class="topnav">
  <a class="active" href="index.php">Inicio</a>
  <a href="eventos.php">Eventos</a>
  <a href="salas.php">Salas</a>
  <a href="equipos.php">Equipos</a>
  <a href="libros.php">Libros</a>
</div>

<div style="padding-left:16px">
	<br>
	<br>
   	<div class="w3-card-4">
	  <div class="w3-container w3-green">
	    <h2>Registrese en la plataforma</h2>
	  </div>
	  <form class="w3-container" method="post" action="Registro.php">
	    <p>      
	    <label class="w3-text-green"><b>Nombre de Usuario</b></label>
	    <input class="w3-input w3-border w3-sand" name="username" type="text" required>
      </p>
      <p>      
      <label class="w3-text-green"><b>Email</b></label>
      <input class="w3-input w3-border w3-sand" name="email" type="text" required>
      </p>
      <p>      
      <label class="w3-text-green"><b>Confirmar Email</b></label>
      <input class="w3-input w3-border w3-sand" name="emailconfirm" type="text" required>
      </p>
	    <p>      
	    <label class="w3-text-green"><b>Contraseña</b></label>
	    <input class="w3-input w3-border w3-sand" name="password" type="password" required></p>
	    <p>
	    <button class="w3-btn w3-green" type="submit">Registrarse</button>
	    </p>
	  </form>
   </div>
</div>

</body>
</html>