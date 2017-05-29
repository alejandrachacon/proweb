<?php
session_start();
if (!isset($_SESSION['rol'])) // Solo se puede solicitar si tiene sesion iniciada
{
  header('Location: /');
  exit();
}

$msg = "";

// Verificar si se hizo un POST para solicitar un equipo
if (isset($_POST['fabricante'], $_POST['nombre'], $_POST['fechaPrestamo']))
{
  include_once dirname(__FILE__) . "/equipos_crud.php";
  // Llamar la funcion que registra la solicitud, esta funcion esta en equipos_crud.php
  if (solicitar_equipo($_SESSION['usuario'], $_POST['fabricante'], $_POST['nombre'], $_POST['fechaPrestamo']))
  {
    $msg = "<span style='color: green;'>Solicitud enviada con exito</span>";
  }
  else
  {
    $msg = "<span style='color: red;'>Hubo un error al realizar la solicitud</span>";
  }

  // TODO: enviar correo de notificacion al admin
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="../css/style.css">
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://unpkg.com/flatpickr/dist/flatpickr.min.css">
<script src="https://unpkg.com/flatpickr"></script>
</head>
<body>

<div class="topnav">
  <?php
    if (!isset($_SESSION['rol']))
    {
      echo '<a href="../index.php">Inicio</a>';
    }
    if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin')
    {
        echo '<a href="../mensajes.php">Mensajes</a>';
    }
  ?>
  <a href="../eventos.php">Eventos</a>
  <a href="../salas.php">Salas</a>
  <a class="active" href="../equipos.php">Equipos</a>
  <a href="../libros.php">Libros</a>
  <?php
		if (isset($_SESSION['rol']))
		{
			echo '<a href="../logout.php">Log out</a>';
		}
	?>
</div>

<div class="w3-container" >
  <h3><?php echo $msg; ?></h3>
  <h2>Formato de solicitud</h2>
  <form class="w3-container" action="solicitar.php?fabricante=<?php if (isset($_GET['fabricante'])) echo $_GET['fabricante'] ?>&nombre=<?php if (isset($_GET['nombre'])) echo $_GET['nombre'] ?>" method="POST" style="width: 50%">
    <label>Fabricante</label>
    <input name="fabricante" class="w3-input" type="text" value="<?php if (isset($_GET['fabricante'])) echo $_GET['fabricante'] ?>" readonly>

    <label>Nombre</label>
    <input name="nombre" class="w3-input" type="text" value="<?php if (isset($_GET['nombre'])) echo $_GET['nombre'] ?>" readonly>

    <label>Fecha de prestamo</label>
    <br>
    <input id="fechaPrestamo" name="fechaPrestamo" type="datetime-local" class="form-control" readonly>
    <br>
    <br>
    <input type="submit" value="Solicitar">
  </form>
</div>

<script>
  $(function () {
    $("#fechaPrestamo").flatpickr({enableTime: true, dateFormat:"Y-m-d H:i", defaultDate: new Date()});
  });
</script>
</body>
</html>