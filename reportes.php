<?php
session_start();
// Verificar que solo pueda entrar el admin
if (!isset($_SESSION['rol']) || (isset($_SESSION['rol']) && $_SESSION['rol'] != 'admin'))
{
  header('Location: index.php');
  exit();
}

include_once dirname(__FILE__) . "/solicitudes/solicitudes_crud.php";
include_once dirname(__FILE__) . "/reportes/reportes_crud.php";
$msg = "";
if (isset($_POST['reporte'], $_POST['item'], $_POST['estado'], $_POST['tipo'], $_POST['usuario']))
{
  $solicitud = buscar_solicitud($_POST['usuario'], $_POST['tipo'], $_POST['item']);
  if (!$solicitud)
  {
    $msg = "<span style='color: red'>Ocurrió un error al registrar el reporte</span>";
  }
  else
  {
    $result = crear_reporte($solicitud['id'], $_POST['estado'], $_POST['comentario']);
    if ($result === 'deteriorado')
    {
      if ($_POST['tipo'] == 'sala')
      {
        $msg = "<span style='color: red'>La " . $_POST['tipo'] . " no se puede devolver porque está deteriorada</span>";
      }
      else
      {
        $msg = "<span style='color: red'>El " . $_POST['tipo'] . " no se puede devolver porque está deteriorado</span>";
      }
    }
    else if ($result)
    {
      $msg = "<span style='color: green'>Reporte registrado satisfactoriamente</span>";
    }
    else
    {
      $msg = "<span style='color: red'>Ocurrió un error al registrar el reporte</span>";
    }
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
        echo '<a class="active" href="reportes.php">Reportes</a>';
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

<div class="w3-container">
  <?php
    echo "<h3>" . $msg . "</h3>";
  ?>
  <br>
  <h3>Buscar usuario</h3>
  <form class="w3-container" action="reportes.php" method="POST" style="width: 50%">
    <label>Usuario</label>
    <input name="usuario" class="w3-input" type="text" value="<?php if (isset($_POST['usuario'])) echo $_POST['usuario']; ?>"required>
    <br>
    <label>Tipo</label><br>
    <select name="tipo">
      <option value="equipo">Equipo</option>
      <option value="libro">Libro</option>
      <option value="sala">Sala</option>
    </select>
    <br><br>
    <input type="submit" name="buscar" value="Buscar">
  </form>
  <br><br>
  <?php
    $html = "";
    // Verificar si ingreso el formulario de buscar usuario
    if (isset($_POST['buscar'], $_POST['usuario'], $_POST['tipo']))
    {
      $solicitudes = get_prestados_usuario($_POST['usuario'], $_POST['tipo']);
      if ($solicitudes && mysqli_num_rows($solicitudes) > 0)
      {
        $html = "<h3>Reporte</h3>";
        $html .= "<form class='w3-container' action='reportes.php' method='POST' name='formreporte' style='width: 50%'>";
        $html .= "<label>Item</label><br>";
        $html .= "<select name='item'>";
        while ($row = mysqli_fetch_array($solicitudes))
        {
          // Buscar todos los items que tiene el usuario para mostrarlo en un <select>
          if ($row['tipo'] == 'equipo' && $_POST['tipo'] == 'equipo')
          {
            $html .= "<option value='" . $row['equipos_nombre'] . "'>" . $row['equipos_nombre'] . "</option>";
          }
          else if ($row['tipo'] == 'libro' && $_POST['tipo'] == 'libro')
          {
            $html .= "<option value='" . $row['libros_isbn'] . "'>" . $row['libros_isbn'] . "</option>";
          }
          else if ($row['tipo'] == 'sala' && $_POST['tipo'] == 'sala')
          {
            $html .= "<option value='" . $row['sala_nombre'] . "'>" . $row['sala_nombre'] . "</option>";
          }
        }
        $html .= "</select><br>";

        $html .= "<label>Estado</label><br>";
        $html .= "<select name='estado'>";
        $html .= "<option value='excelente'>Excelente</option>";
        $html .= "<option value='bueno'>Bueno</option>";
        $html .= "<option value='regular'>Regular</option>";
        $html .= "<option value='deteriorado'>Deteriorado</option>";
        $html .= "<option value='devuelto'>Devuelto</option>";
        $html .= "</select><br>";
        
        $html .= "<label>Comentario</label><br>";
        $html .= "<textarea rows='4' name='comentario'></textarea><br>";

        $html .= "<input class='w3-input' type='hidden' name='tipo' value='" . $_POST['tipo'] . "'/>";
        $html .= "<input class='w3-input' type='hidden' name='usuario' value='" . $_POST['usuario'] . "'/>";
        $html .= "<br><input type='submit' name='reporte' value='Registrar reporte'/>";
        $html .= "</form>";
      }
      else
      {
        $html = "<h3 style='color: green;'>Este usuario no tiene ningún " . $_POST['tipo'] . " prestado</h3>";
      }
    }
    echo $html;
  ?>
</div>
</body>
</html>