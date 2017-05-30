<?php
session_start();
if (!isset($_SESSION['rol'])) // Solo se puede solicitar si tiene sesion iniciada
{
  header('Location: /');
  exit();
}

$msg = "";
include_once dirname(__FILE__) . "/solicitudes/solicitudes_crud.php";
include_once dirname(__FILE__) . "/equipos/equipos_crud.php";
// Verificar si se hizo un POST para solicitar un equipo
if (isset($_POST['tipo'], $_POST['fabricante'], $_POST['nombre'], $_POST['fechaPrestamo']))
{
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
else if (isset($_POST['tipo'], $_POST['isbn'], $_POST['fechaPrestamo']))
{
  if (solicitar_libro($_SESSION['usuario'], $_POST['isbn'], $_POST['fechaPrestamo']))
  {
    $msg = "<span style='color: green;'>Solicitud enviada con exito</span>";
  }
  else
  {
    $msg = "<span style='color: red;'>Hubo un error al realizar la solicitud</span>";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="css/style.css">
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://unpkg.com/flatpickr/dist/flatpickr.min.css">
<script src="https://unpkg.com/flatpickr"></script>
</head>
<body>

<div class="topnav">
  <?php
    if (!isset($_SESSION['rol']))
    {
      echo '<a href="index.php">Inicio</a>';
    }
    if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin')
    {
        echo '<a href="mensajes.php">Mensajes</a>';
        echo '<a href="reportes.php">Reportes</a>';
    }
  ?>
  <a href="eventos.php">Eventos</a>
  <a href="salas.php">Salas</a>
  <a class="active" href="equipos.php">Equipos</a>
  <a href="libros.php">Libros</a>
  <?php
		if (isset($_SESSION['rol']))
		{
			echo '<a href="logout.php">Log out</a>';
		}
	?>
</div>

<div class="w3-container" >
  <h3><?php echo $msg; ?></h3>
  <h2>Formato de solicitud de <?php echo $_GET['tipo']; ?></h2>
  <?php
    if (isset($_GET['tipo']) && $_GET['tipo'] == 'equipo')
    {
  ?>
      <form class="w3-container" action="solicitar.php?tipo=equipo&fabricante=<?php if (isset($_GET['fabricante'])) echo $_GET['fabricante'] ?>&nombre=<?php if (isset($_GET['nombre'])) echo $_GET['nombre'] ?>" method="POST" style="width: 50%">
        <label>Fabricante</label>
        <input name="fabricante" class="w3-input" type="text" value="<?php if (isset($_GET['fabricante'])) echo $_GET['fabricante'] ?>" readonly>

        <label>Nombre</label>
        <input name="nombre" class="w3-input" type="text" value="<?php if (isset($_GET['nombre'])) echo $_GET['nombre'] ?>" readonly>

        <label>Fecha de prestamo</label>
        <br>
        <input id="fechaPrestamo" name="fechaPrestamo" type="datetime-local" class="form-control" readonly>
        <br>
        <br>
        <input type="hidden" name="tipo" value="<?php if (isset($_GET['tipo'])) echo $_GET['tipo']; else echo 'equipo';?>">
        <input type="submit" value="Solicitar">
      </form>
  <?php
    }
    else if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro')
    {
  ?>
      <form class="w3-container" action="solicitar.php?tipo=libro&autor=<?php if (isset($_GET['autor'])) echo $_GET['autor']; ?>&titulo=<?php if (isset($_GET['titulo'])) echo $_GET['titulo']; ?>&isbn=<?php if (isset($_GET['isbn'])) echo $_GET['isbn']; ?>" method="POST" style="width: 50%">
        <label>Autor</label>
        <input name="autor" class="w3-input" type="text" value="<?php if (isset($_GET['autor'])) echo $_GET['autor'] ?>" readonly>

        <label>Titulo</label>
        <input name="titulo" class="w3-input" type="text" value="<?php if (isset($_GET['titulo'])) echo $_GET['titulo'] ?>" readonly>

        <label>ISBN</label>
        <input name="isbn" class="w3-input" type="text" value="<?php if (isset($_GET['isbn'])) echo $_GET['isbn'] ?>" readonly>

        <label>Fecha de prestamo</label>
        <br>
        <input id="fechaPrestamo" name="fechaPrestamo" type="datetime-local" class="form-control" readonly>
        <br>
        <br>
        <input type="hidden" name="tipo" value="<?php if (isset($_GET['tipo'])) echo $_GET['tipo']; else echo 'equipo';?>">
        <input type="submit" value="Solicitar">
      </form>
  <?php
    }
  ?>
</div>

<script>
  $(function () {
    $("#fechaPrestamo").flatpickr({enableTime: true, dateFormat:"Y-m-d H:i", defaultDate: new Date()});
  });
</script>
</body>
</html>