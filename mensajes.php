<?php
session_start();
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
  <a href="index.php">Inicio</a>
  <?php
    if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin')
    {
        echo '<a class="active" href="mensajes.php">Mensajes</a>';
    }
  ?>
  <a href="eventos.php">Eventos</a>
  <a href="salas.php">Salas</a>
  <a href="equipos.php">Equipos</a>
  <a href="libros.php">Libros</a>
	<?php
		if (isset($_SESSION['rol']))
		{
			echo '<a href="logout.php">Log out</a>';
		}
	?>
</div>

</body>
</html>