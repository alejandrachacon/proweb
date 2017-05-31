<?php
session_start();
// Verificar que solo pueda entrar el admin
if (!isset($_SESSION['rol']) || (isset($_SESSION['rol']) && $_SESSION['rol'] != 'admin'))
{
  header('Location: index.php');
  exit();
}

if (!isset($_GET['tipo']) || ($_GET['tipo'] != 'equipo' && $_GET['tipo'] != 'libro' && $_GET['tipo'] != 'sala'))
{
  header('Location: index.php');
  exit();
}

$msg = "";

include_once dirname(__FILE__) . "/equipos/equipos_crud.php";
include_once dirname(__FILE__) . "/libros/libros_crud.php";
include_once dirname(__FILE__) . "/salas/salas_crud.php";
include_once dirname(__FILE__) . "/eventos/eventos_crud.php";

// Verificar si estamos eliminando un equipo
if (isset($_POST['eliminar'], $_POST['nombre'], $_GET['tipo']) && $_GET['tipo'] == 'equipo')
{
  if (eliminar_equipo($_POST['nombre']))
  {
    $msg = "<span style='color: green'>Equipo eliminado</span>";
  }
  else
  {
    $msg = "<span style='color: red'>Error al eliminar equipo</span>";
  }
}
// Verificar si estamos eliminando un libro
else if (isset($_POST['eliminar'], $_POST['isbn']))
{
  if (eliminar_libro($_POST['isbn']))
  {
    $msg = "<span style='color: green'>Libro eliminado</span>";
  }
  else
  {
    $msg = "<span style='color: red'>Error al eliminar libro</span>";
  }
}
// Verificar si estamos eliminando una sala
else if (isset($_POST['eliminar'], $_POST['nombre'], $_GET['tipo']) && $_GET['tipo'] == 'sala')
{
  if (eliminar_sala($_POST['nombre']))
  {
    $msg = "<span style='color: green'>Sala eliminada</span>";
  }
  else
  {
    $msg = "<span style='color: red'>Error al eliminar sala</span>";
  }
}
// Verificar si estamos eliminando un evento
else if (isset($_POST['eliminar'], $_POST['nombre'], $_GET['tipo']) && $_GET['tipo'] == 'evento')
{
  if (eliminar_evento($_GET['id']))
  {
    $msg = "<span style='color: green'>Evento eliminado</span>";
  }
  else
  {
    $msg = "<span style='color: red'>Error al eliminar evento</span>";
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
  <a href="equipos.php">Equipos</a>
  <a href="libros.php">Libros</a>
  <?php
		if (isset($_SESSION['rol']))
		{
			echo '<a href="logout.php">Log out</a>';
		}
	?>
</div>

<?php echo $msg; ?>
<br>
<h3>Eliminar <?php if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro') echo "Libro"; else if (isset($_GET['tipo']) && $_GET['tipo'] == 'equipo') echo "Equipo"; else if (isset($_GET['tipo']) && $_GET['tipo'] == 'evento') echo "Evento"; else echo "Sala" ?></h3>
<?php
  $html = "";
  // Formulario de libros
  if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro')
  {
    $libro = buscar_libro($_GET['isbn']);
    if ($libro)
    {
      $html .= "<form class='w3-container' action='eliminar.php?tipo=libro&isbn=" . $libro['isbn'] . "' method='post' style='width: 50%' enctype='multipart/form-data'>";
      $html .= "<label>Autor</label>";
      $html .= "<input class='w3-input' type='text' name='autor' value='" . $libro['autor'] . "' required readonly/>";
      $html .= "<label>Titulo</label>";
      $html .= "<input class='w3-input' type='text' name='titulo' value='" . $libro['titulo'] . "' required readonly/>";
      $html .= "<label>ISBN</label>";
      $html .= "<input class='w3-input' type='text' name='isbn' value='" . $libro['isbn'] . "' required readonly/><br>";
      $html .= "<label>Editorial</label>";
      $html .= "<input class='w3-input' type='text' name='editorial' value='" . $libro['editorial'] . "' required readonly/>";
      $html .= "<label># de páginas</label>";
      $html .= "<input class='w3-input' type='number' name='paginas' value='" . $libro['paginas'] . "' required readonly/><br>";
      $html .= "<label># de copias disponibles</label>";
      $html .= "<input class='w3-input' type='number' name='disponible' value='" . $libro['disponibles'] . "' required readonly/><br>";
      $html .= "<label># total de copias</label>";
      $html .= "<input class='w3-input' type='number' name='copias' value='" . $libro['total'] . "' required readonly/><br>";
      $html .= "<input type='submit' name='eliminar' value='Eliminar' />";
      $html .= "</form>";
    }
  }
  // Formulario de equipos
  else if (isset($_GET['tipo']) && $_GET['tipo'] == 'equipo')
  {
    $equipo = buscar_equipo($_GET['nombre']);
    
    if ($equipo)
    {
      $html .= "<form class='w3-container' action='eliminar.php?tipo=equipo&nombre=" . $equipo['nombre'] . "' method='post' style='width: 50%' enctype='multipart/form-data'>";
      $html .= "<label>Fabricante</label>";
      $html .= "<input class='w3-input' type='text' name='fabricante' value='" . $equipo['fabricante'] . "' required readonly/>";
      $html .= "<label>Nombre</label>";
      $html .= "<input class='w3-input' type='text' name='nombre' value='" . $equipo['nombre'] . "' required readonly/>";
      $html .= "<label># de serie</label>";
      $html .= "<input class='w3-input' type='number' name='serie' value='" . $equipo['serie'] . "' required readonly/>";
      $html .= "<label># disponible de equipos</label>";
      $html .= "<input class='w3-input' type='number' name='disponible' value='" . $equipo['disponibles'] . "' required readonly/><br>";
      $html .= "<label># total de equipos</label>";
      $html .= "<input class='w3-input' type='number' name='numeroEquipos' value='" . $equipo['total'] . "' required readonly/><br>";
      $html .= "<input type='submit' name='eliminar' value='Eliminar' />";
      $html .= "</form>";
    }
  }
  // Formulario de salas
  else if (isset($_GET['tipo']) && $_GET['tipo'] == 'sala')
  {
    $sala = buscar_sala($_GET['nombre']);
    
    if ($sala)
    {
      $html .= "<form class='w3-container' action='eliminar.php?tipo=sala&nombre=" . $sala['nombre'] . "' method='post' style='width: 50%'>";
      $html .= "<label>Nombre</label>";
      $html .= "<input class='w3-input' type='text' name='nombre' value='" . $sala['nombre'] . "' required readonly/><br>";
      $html .= "<input type='submit' name='eliminar' value='Eliminar' />";
      $html .= "</form>";
    }
  }
    // Formulario de eventos
  else if (isset($_GET['tipo']) && $_GET['tipo'] == 'evento')
  {
    $evento = buscar_evento($_GET['id']);
    
    if ($evento)
    {
      $html .= "<form class='w3-container' action='eliminar.php?tipo=evento&id=" . $evento['id'] . "' method='post' style='width: 50%'>";
      $html .= "<label>Nombre</label>";
      $html .= "<input class='w3-input' type='text' name='nombre' value='" . $evento['nombre'] . "' required readonly/><br>";
      $html .= "<input type='submit' name='eliminar' value='Eliminar' />";
      $html .= "</form>";
    }
  }

  echo $html;
?>

</body>
</html>