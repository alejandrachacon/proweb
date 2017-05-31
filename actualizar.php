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
include_once dirname(__FILE__) . "/eventos/eventos_crud.php";

// Verificar si estamos actualizando un equipo
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
  if (actualizar_equipo($_POST['fabricante'], $_POST['nombre'], $_POST['serie'], $_POST['disponible'], $_POST['numeroEquipos'], $fileName))
  {
    $msg = "<span style='color: green'>Equipo actualizado</span>";
  }
  else
  {
    $msg = "<span style='color: red'>Error al actualizar equipo</span>";
  }
}
// Verificar si estamos actualizando un libro
else if (isset($_POST['titulo'], $_POST['autor'], $_POST['editorial'], $_POST['paginas'], $_POST['isbn'], $_POST['disponible'], $_POST['copias']))
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
  if (actualizar_libro($_POST['titulo'], $_POST['autor'], $_POST['editorial'], $_POST['paginas'], $_POST['isbn'], $_POST['disponible'], $_POST['copias'], $fileName))
  {
    $msg = "<span style='color: green'>Libro actualizado</span>";
  }
  else
  {
    $msg = "<span style='color: red'>Error al actualizar libro</span>";
  }
}
// Verificar si estamos actualizando un evento
else if (isset($_POST['nombre'], $_POST['fechainicio'], $_POST['fechafin'], $_POST['informacion'], $_POST['lugar'], $_POST['sala_nombre'], $_POST['id']))
{
  if (actualizar_evento($_POST['id'], $_POST['fechainicio'], $_POST['fechafin'], $_POST['lugar'], $_POST['sala_nombre'], $_POST['nombre'], $_POST['informacion']))
  {
    $msg = "<span style='color: green'>Evento actualizado</span>";
  }
  else
  {
    $msg = "<span style='color: red'>Error al actualizar evento</span>";
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
<h3>Actualizar <?php if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro') echo "Libro"; else if (isset($_GET['tipo']) && $_GET['tipo'] == 'equipo') echo "Equipo"; else if (isset($_GET['tipo']) && $_GET['tipo'] == 'evento') echo "Evento"; else echo "Sala" ?></h3>
<?php
  $html = "";
  // Formulario de libros
  if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro')
  {
    $libro = buscar_libro($_GET['isbn']);
    if ($libro)
    {
      $html .= "<form class='w3-container' action='actualizar.php?tipo=libro&isbn=" . $libro['isbn'] . "' method='post' style='width: 50%' enctype='multipart/form-data'>";
      $html .= "<label>Autor</label>";
      $html .= "<input class='w3-input' type='text' name='autor' value='" . $libro['autor'] . "' required/>";
      $html .= "<label>Titulo</label>";
      $html .= "<input class='w3-input' type='text' name='titulo' value='" . $libro['titulo'] . "' required/>";
      $html .= "<label>ISBN</label>";
      $html .= "<input class='w3-input' type='text' name='isbn' value='" . $libro['isbn'] . "' required readonly/><br>";
      $html .= "<label>Editorial</label>";
      $html .= "<input class='w3-input' type='text' name='editorial' value='" . $libro['editorial'] . "' required/>";
      $html .= "<label># de páginas</label>";
      $html .= "<input class='w3-input' type='number' name='paginas' value='" . $libro['paginas'] . "' required/><br>";
      $html .= "<label># de copias disponibles</label>";
      $html .= "<input class='w3-input' type='number' name='disponible' value='" . $libro['disponibles'] . "' required/><br>";
      $html .= "<label># total de copias</label>";
      $html .= "<input class='w3-input' type='number' name='copias' value='" . $libro['total'] . "' required/><br>";
      $html .= "<label>Imagen</label>";
      $html .= "<input class='w3-input' type='file' name='imagen' id='imagen'/><br>";
      $html .= "<input type='submit' value='Actualizar' />";
      $html .= "</form>";
    }
  }
  // Formulario de eventos
  else
  {
      if (isset($_GET['tipo']) && $_GET['tipo'] == 'evento')
  {
    $evento = buscar_evento($_GET['id']);
    if ($evento)
    {
      $html .= "<form class='w3-container' action='actualizar.php?tipo=evento&id=" . $evento['id'] . "' method='post' style='width: 50%' enctype='multipart/form-data'>";
      $html .= "<label>Nombre</label>";
      $html .= "<input class='w3-input' type='text' name='nombre' value='" . $libro['nombre'] . "' required/> <br>";
      $html .= "<label>Descripción</label>";
      $html .= "<input class='w3-input' type='text' name='Descripción' value='" . $libro['informacion'] . "' required/><br>";
      $html .= "<label>Lugar</label>";
      $html .= "<input class='w3-input' type='text' name='lugar' value='" . $libro['lugar'] . "' required/>";
      $html .= "<label>Fecha de Inicio</label>";
    /*  $html .= " dia <input class='w3-input' type='text' name='dia' value='" . $libro['fechainicio'] . "' required readonly/><br>";
      $html .= "<label>Editorial</label>";
      $html .= "<input class='w3-input' type='text' name='editorial' value='" . $libro['fechafin'] . "' required/>";*/
      $html .= "<input type='submit' value='Actualizar' />";
      $html .= "</form>";
    }
  }
  else{

    $equipo = buscar_equipo($_GET['nombre']);
    
      if ($equipo)
      {
        $html .= "<form class='w3-container' action='actualizar.php?tipo=equipo&nombre=" . $equipo['nombre'] . "' method='post' style='width: 50%' enctype='multipart/form-data'>";
        $html .= "<label>Fabricante</label>";
        $html .= "<input class='w3-input' type='text' name='fabricante' value='" . $equipo['fabricante'] . "' required/>";
        $html .= "<label>Nombre</label>";
        $html .= "<input class='w3-input' type='text' name='nombre' value='" . $equipo['nombre'] . "' required readonly/>";
        $html .= "<label># de serie</label>";
        $html .= "<input class='w3-input' type='number' name='serie' value='" . $equipo['serie'] . "' required/>";
        $html .= "<label># disponible de equipos</label>";
        $html .= "<input class='w3-input' type='number' name='disponible' value='" . $equipo['disponibles'] . "' required/><br>";
        $html .= "<label># total de equipos</label>";
        $html .= "<input class='w3-input' type='number' name='numeroEquipos' value='" . $equipo['total'] . "' required/><br>";
        $html .= "<label>Imagen</label>";
        $html .= "<input class='w3-input' type='file' name='imagen' id='imagen'/><br>";
        $html .= "<input type='submit' value='Actualizar' />";
        $html .= "</form>";
      }
    }
  }

  echo $html;
?>

</body>
</html>