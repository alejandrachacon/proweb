<?php
session_start();
// Verificar que solo pueda entrar el admin
if (!isset($_SESSION['rol']) || (isset($_SESSION['rol']) && $_SESSION['rol'] != 'admin'))
{
  header('Location: index.php');
  exit();
}

$msg = "";

include_once dirname(__FILE__) . "/equipos/equipos_crud.php";

// Verificar si estamos agregando un equipo
if (isset($_POST['fabricante'], $_POST['nombre'], $_POST['serie'], $_POST['numeroEquipos']))
{
  // Verificar que no haya error subiendo la imagen
  if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] > 0)
  {
    $msg = "<span style='color: red'>Error subiendo imagen</span>";
  }
  // Generar un nombre unico para la imagen
  $fileName = null;
  if (file_exists($_FILES['imagen']['tmp_name'])) // Verificar que se haya subido la imagen
  {
    // Check file type
    $imageType = exif_imagetype($_FILES['imagen']['tmp_name']);
    if ($imageType == 2) // 2 = jpeg
    {
      $fileName = uniqid();
      $fileName = "uploads/" . $fileName . ".jpg";
      move_uploaded_file($_FILES['imagen']['tmp_name'], $fileName);
    }
    else if ($imageType == 3) // 3 = png
    {
      $fileName = uniqid();
      $fileName = "uploads/" . $fileName . ".png";
      move_uploaded_file($_FILES['imagen']['tmp_name'], $fileName);
    }
    else
    {
      $msg = "<span style='color: red'>Sólo se aceptan imágenes jpeg o png.</span>";
    }
  }
  if (crear_equipo($_POST['fabricante'], $_POST['nombre'], $_POST['serie'], $_POST['numeroEquipos'], $fileName))
  {
    $msg = "<span style='color: green'>Equipo agregado</span>";
  }
  else
  {
    $msg = "<span style='color: red'>Error al agregar equipo</span>";
  }
}
// Verificar si estamos agregando un libro
else if (isset($_POST['titulo'], $_POST['autor'], $_POST['edicion'], $_POST['editorial'], $_POST['paginas'], $_POST['isbn'], $_POST['copias']))
{
  // TODO: logic...
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

<h3>Seleccione que desea agregar</h3>
<form class="w3-container" action="agregar.php" method="get" id="escoger_tipo">
  <!--<label for="tipo">Tipo</label>-->
  <br>
  <select name="tipo" form="escoger_tipo">
    <option value="equipo">Equipo</option>
    <option value="libro">Libro</option>
  </select>
  <br><br>
  <input type="submit" value="Cargar">
</form>

<?php echo $msg; ?>
<br>
<h3>Agregar <?php if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro') echo "Libro"; else echo "Equipo"; ?></h3>
<?php
  $html = "";
  // Formulario de libros
  if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro')
  {
    // TODO: form de libros
  }
  // Formulario de equipos
  else
  {
    $html .= "<form class='w3-container' action='agregar.php' method='post' style='width: 50%' enctype='multipart/form-data'>";
    $html .= "<label>Fabricante</label>";
    $html .= "<input class='w3-input' type='text' name='fabricante' required/>";
    $html .= "<label>Nombre</label>";
    $html .= "<input class='w3-input' type='text' name='nombre' required/>";
    $html .= "<label># de serie</label>";
    $html .= "<input class='w3-input' type='number' name='serie' required/>";
    $html .= "<label># de equipos</label>";
    $html .= "<input class='w3-input' type='number' name='numeroEquipos' required/><br>";
    $html .= "<label>Imagen</label>";
    $html .= "<input class='w3-input' type='file' name='imagen' id='imagen'/><br>";
    $html .= "<input type='submit' value='Agregar' />";
    $html .= "</form>";
  }

  echo $html;
?>

</body>
</html>