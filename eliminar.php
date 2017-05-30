<?php
session_start();
// Verificar que solo pueda entrar el admin
if (!isset($_SESSION['rol']) || (isset($_SESSION['rol']) && $_SESSION['rol'] != 'admin'))
{
  header('Location: index.php');
  exit();
}

if (!isset($_GET['tipo']) || ($_GET['tipo'] != 'equipo' && $_GET['tipo'] != 'libro'))
{
  header('Location: index.php');
  exit();
}

$msg = "";

include_once dirname(__FILE__) . "/equipos/equipos_crud.php";
include_once dirname(__FILE__) . "/libros/libros_crud.php";

// Verificar si estamos eliminando un equipo
if (isset($_POST['eliminar'], $_POST['nombre']))
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
  <a class="active" href="equipos.php">Equipos</a>
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
<h3>Eliminar <?php if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro') echo "Libro"; else echo "Equipo"; ?></h3>
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
  else
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

  echo $html;
?>

</body>
</html>