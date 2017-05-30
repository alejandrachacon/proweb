<?php
session_start();
// Verificar que solo pueda entrar el admin
if (!isset($_SESSION['rol']) || (isset($_SESSION['rol']) && $_SESSION['rol'] != 'admin'))
{
  header('Location: index.php');
  exit();
}

$msg = "";

  /*  if(isset($)){

    }
    elseif (condition) {
      # code...
    }*/


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

<h3>Ingrese datos de búsqueda</h3>

<form class="w3-container" action="busquedaAvanzada.php" method="get" id="escoger_tipo">
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
<h3>Buscar <?php if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro') echo "Libro"; else echo "Equipo"; ?></h3>
<?php
  $html = "";
  // Formulario de libros
  if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro')
  {
    $html .= "<form class='w3-container' action='busquedaAvanzada.php' method='post' style='width: 50%' enctype='multipart/form-data'>";
    $html .= "<label>Autor</label>";
    $html .= "<input class='w3-input' type='text' name='autor' />";
    $html .= "<label>Titulo</label>";
    $html .= "<input class='w3-input' type='text' name='titulo' />";
    $html .= "<label>ISBN</label>";
    $html .= "<input class='w3-input' type='text' name='isbn' /><br>";
    $html .= "<label>Editorial</label>";
    $html .= "<input class='w3-input' type='text' name='editorial' />";
    $html .= "<label>Edicion</label>";
    $html .= "<input class='w3-input' type='number' name='edicion' /><br>";
    $html .= "<label># de páginas</label>";
    $html .= "<input class='w3-input' type='number' name='paginas' /><br>";
    $html .= "<label># de copias</label>";
    $html .= "<input class='w3-input' type='number' name='copias' /><br>";
 //   $html .= "<label>Imagen</label>";
//    $html .= "<input class='w3-input' type='file' name='imagen' id='imagen'/><br>";
    $html .= "<input type='submit' value='Buscar' />";
    $html .= "</form>";
  }
  // Formulario de equipos
  else
  {
    $html .= "<form class='w3-container' action='busquedaAvanzada.php' method='post' style='width: 50%' enctype='multipart/form-data'>";
    $html .= "<label>Fabricante</label>";
    $html .= "<input class='w3-input' type='text' name='fabricante' />";
    $html .= "<label>Nombre</label>";
    $html .= "<input class='w3-input' type='text' name='nombre' />";
    $html .= "<label># de serie</label>";
    $html .= "<input class='w3-input' type='number' name='serie' />";
    $html .= "<label># de equipos</label>";
    $html .= "<input class='w3-input' type='number' name='numeroEquipos' /><br>";
 //   $html .= "<label>Imagen</label>";
//    $html .= "<input class='w3-input' type='file' name='imagen' id='imagen'/><br>";
    $html .= "<input type='submit' value='Buscar' />";
    $html .= "</form>";
  }

  echo $html;
?>

</body>
</html>