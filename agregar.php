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
include_once dirname(__FILE__) . "/libros/libros_crud.php";
include_once dirname(__FILE__) . "/salas/salas_crud.php";
include_once dirname(__FILE__) . "/eventos/eventos_crud.php";

// Verificar si estamos agregando un equipo
if (isset($_POST['fabricante'], $_POST['nombre'], $_POST['serie'], $_POST['numeroEquipos']) && $_GET['tipo'] == 'equipo')
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
      if (!file_exists('uploads/'))
      {
        mkdir('uploads/', 0777, true);
      }
      move_uploaded_file($_FILES['imagen']['tmp_name'], $fileName);
    }
    else if ($imageType == 3) // 3 = png
    {
      $fileName = uniqid();
      $fileName = "uploads/" . $fileName . ".png";
      if (!file_exists('uploads/'))
      {
        mkdir('uploads/', 0777, true);
      }
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
      if (!file_exists('uploads/'))
      {
        mkdir('uploads/', 0777, true);
      }
      move_uploaded_file($_FILES['imagen']['tmp_name'], $fileName);
    }
    else if ($imageType == 3) // 3 = png
    {
      $fileName = uniqid();
      $fileName = "uploads/" . $fileName . ".png";
      if (!file_exists('uploads/'))
      {
        mkdir('uploads/', 0777, true);
      }
      move_uploaded_file($_FILES['imagen']['tmp_name'], $fileName);
    }
    else
    {
      $msg = "<span style='color: red'>Sólo se aceptan imágenes jpeg o png.</span>";
    }
  }
  if (crear_libro($_POST['titulo'], $_POST['autor'], $_POST['edicion'], $_POST['editorial'], $_POST['paginas'], $_POST['isbn'], $_POST['copias'], $fileName))
  {
    $msg = "<span style='color: green'>Libro agregado</span>";
  }
  else
  {
    $msg = "<span style='color: red'>Error al agregar libro</span>";
  }
}
// Verificar si estamos agregando una sala
else if (isset($_POST['nombre'], $_GET['tipo']) && $_GET['tipo'] == 'sala')
{
  if (crear_sala($_POST['nombre']))
  {
    $msg = "<span style='color: green'>Sala agregada</span>";
  }
  else
  {
    $msg = "<span style='color: red'>Error al agregar sala</span>";
  }
}

// Verificar si estamos agregando un evento
else if (isset($_POST['nombre'], $_GET['tipo']) && $_GET['tipo'] == 'evento')
{
  if (crear_evento($_POST['nombre']))
  {
    $msg = "<span style='color: green'>Evento agregado</span>";
  }
  else
  {
    $msg = "<span style='color: red'>Error al agregar evento</span>";
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

<h3>Seleccione que desea agregar</h3>
<form class="w3-container" action="agregar.php" method="get" id="escoger_tipo">
  <!--<label for="tipo">Tipo</label>-->
  <br>
  <select name="tipo" form="escoger_tipo">
    <option value="equipo">Equipo</option>
    <option value="libro">Libro</option>
    <option value="sala">Sala</option>
    <option value="evento">Evento</option>
  </select>
  <br><br>
  <input type="submit" value="Cargar">
</form>

<?php echo $msg; ?>
<br>
<h3>Agregar <?php if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro') echo "Libro"; else if (isset($_GET['tipo']) && $_GET['tipo'] == 'equipo') echo "Equipo"; else if (isset($_GET['tipo']) && $_GET['tipo'] == 'evento') echo "Evento"; else echo "Sala" ?></h3>
<?php
  $html = "";
  // Formulario de libros
  if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro')
  {
    $html .= "<form class='w3-container' action='agregar.php?tipo=libro' method='post' style='width: 50%' enctype='multipart/form-data'>";
    $html .= "<label>Autor</label>";
    $html .= "<input class='w3-input' type='text' name='autor' required/>";
    $html .= "<label>Titulo</label>";
    $html .= "<input class='w3-input' type='text' name='titulo' required/>";
    $html .= "<label>ISBN</label>";
    $html .= "<input class='w3-input' type='text' name='isbn' required/><br>";
    $html .= "<label>Editorial</label>";
    $html .= "<input class='w3-input' type='text' name='editorial' required/>";
    $html .= "<label>Edicion</label>";
    $html .= "<input class='w3-input' type='number' name='edicion' required/><br>";
    $html .= "<label># de páginas</label>";
    $html .= "<input class='w3-input' type='number' name='paginas' required/><br>";
    $html .= "<label># de copias</label>";
    $html .= "<input class='w3-input' type='number' name='copias' required/><br>";
    $html .= "<label>Imagen</label>";
    $html .= "<input class='w3-input' type='file' name='imagen' id='imagen'/><br>";
    $html .= "<input type='submit' value='Agregar' />";
    $html .= "</form>";
  }
  // Formulario de equipos
  else if (isset($_GET['tipo']) && $_GET['tipo'] == 'equipo')
  {
    $html .= "<form class='w3-container' action='agregar.php?tipo=equipo' method='post' style='width: 50%' enctype='multipart/form-data'>";
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
  // Formulario de sala
  else if (isset($_GET['tipo']) && $_GET['tipo'] == 'sala')
  {
    $html .= "<form class='w3-container' action='agregar.php?tipo=sala' method='post' style='width: 50%' enctype='multipart/form-data'>";
    $html .= "<label>Nombre</label>";
    $html .= "<input class='w3-input' type='text' name='nombre' required/><br>";
    $html .= "<input type='submit' value='Agregar' />";
    $html .= "</form>";
  }
    else if (isset($_GET['tipo']) && $_GET['tipo'] == 'evento')
  {
    $html .= "<form class='w3-container' action='agregar.php?tipo=evento' method='post' style='width: 50%' enctype='multipart/form-data'>";
    $html .= "<label>Nombre</label>";
    $html .= "<input class='w3-input' type='text' name='nombre' required/><br>";
    $html .= "<label>Descripción</label>";
    $html .= "<input class='w3-input' type='text' name='nombre' required/><br>";
    $html .= "<label>Fecha de Inicio</label>";
    $html .= "<input class='w3-input' type='text' name='fechainicio' required/><br>";
    $html .= "<label>Fecha de Fin</label>";
    $html .= "<input class='w3-input' type='text' name='fechafin' required/><br>";
    $html .= "<input type='submit' value='Agregar' />";
    $html .= "</form>";
  }

  echo $html;
?>

</body>
</html>