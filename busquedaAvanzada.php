<?php
session_start();

  include_once dirname(__FILE__) . "/libros/libros_busqueda.php";
  include_once dirname(__FILE__) . "/equipos/equipos_busqueda.php";



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

<br>
<h3>Buscar <?php if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro') echo "Libro"; else echo "Equipo"; ?></h3>
<?php
  $html = "";
  // Formulario de libros
  if (isset($_GET['tipo']) && $_GET['tipo'] == 'libro')
  {
    $html .= "<form class='w3-container' id = 'formulario' action='libros.php' method='post' style='width: 50%' enctype='multipart/form-data'>";
    $html .= "<label>Autor</label>";
    $html .= "<input class='w3-input' type='text' name='autor' /><br>";
    $html .= "<label>Titulo</label>";
    $html .= "<input class='w3-input' type='text' name='titulo' /><br>";
    $html .= "<label>Disponibilidad</label>";
    $html .= '<br> <select name="disponibles" form="formulario">';
    $html .= '<option value="si">Si</option>';
    $html .= '<option value="no">No</option>';
    $html .= ' </select> ';
    $html .= ' <br>';
    $html .= "<label>Editorial</label>";
    $html .= "<input class='w3-input' type='text' name='editorial' /> <br><br>";
    $html .= "<input type='submit' value='Buscar' />";
    $html .= "</form>";
  }
  // Formulario de equipos
  else
  {
    $html .= "<form class='w3-container' id = 'formulario' action='equipos.php' method='post' style='width: 50%' enctype='multipart/form-data'>";
    $html .= "<label>Fabricante</label>";
    $html .= "<input class='w3-input' type='text' name='fabricante' /><br>";
    $html .= "<label>Disponibilidad</label><br>";
    $html .= '<br> <select name="disponibles" form="formulario"><br><br>';
    $html .= '<option value="si">Si</option>';
    $html .= '<option value="no">No</option>';
    $html .= ' </select> <br> ';
    $html .= ' <br>';
    $html .= "<label>Nombre</label>";
    $html .= "<input class='w3-input' type='text' name='nombre' /> <br><br>";
    $html .= "<input type='submit' value='Buscar' />";
    $html .= "</form>";
  }

  echo $html;
?>

</body>
</html>