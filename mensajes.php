<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin')
{
	header('Location: index.php');
	exit();
}

include dirname(__FILE__) . "/solicitudes/solicitudes_crud.php";

$msg = "";

// Si se está haciendo un POST significa que se está aprobando/declinando una solicitud
if (isset($_POST['solicitud'], $_POST['decision'], $_POST['usuario'], $_POST['tipo'], $_POST['fechaVencimiento']))
{
  // Aprobar solicitud
  if ($_POST['decision'] == 'Aprobar')
  {
    $reporteCada = null;
    if (isset($_POST['reporteCada']))
    {
      $reporteCada = $_POST['reporteCada'];
    }
    // Si es un equipo, llamar la funcion de aprobar solicitud de equipo
    if ($_POST['tipo'] == 'equipo')
    {
      if (!$reporteCada)
      {
        $msg = "<span style='color: red'>El campo <b>Reporte cada</b> es obligatorio</span>";
      }
      else
      {
        if (aprobar_equipo($_POST['solicitud'], $_POST['usuario'], $_POST['tipo'], $_POST['fechaVencimiento'], $_POST['equipos_nombre'], $reporteCada))
        {
          $msg = "<span style='color: green'>Solicitud aprobada</span>";
        }
        else
        {
          $msg = "<span style='color: red'>Ocurrió un error al aprobar la solicitud</span>";
        }
      }
    }
    // Si es un libro, llamar la funcion de aprobar solicitud de libro
    else if ($_POST['tipo'] == 'libro')
    {
      if (!$reporteCada)
      {
        $msg = "<span style='color: red'>El campo <b>Reporte cada</b> es obligatorio</span>";
      }
      else
      {
        if (aprobar_libro($_POST['solicitud'], $_POST['usuario'], $_POST['tipo'], $_POST['fechaVencimiento'], $reporteCada))
        {
          $msg = "<span style='color: green'>Solicitud aprobada</span>";
        }
        else
        {
          $msg = "<span style='color: red'>Ocurrió un error al aprobar la solicitud</span>";
        }
      }
    }
    // Si es una sala, llamar la funcion de aprobar solicitud de sala
    else if ($_POST['tipo'] == 'sala')
    {
      if (aprobar_sala($_POST['solicitud'], $_POST['usuario'], $_POST['tipo'], $_POST['fechaVencimiento'], $reporteCada))
      {
        $msg = "<span style='color: green'>Solicitud aprobada</span>";
      }
      else
      {
        $msg = "<span style='color: red'>Ocurrió un error al aprobar la solicitud</span>";
      }
    }
  }
  // Declinar solicitud
  else if ($_POST['decision'] == 'Declinar' && isset($_POST['comentario']))
  {
    if (declinar_solicitud($_POST['solicitud']))
    {
      $msg = "<span style='color: green'>Solicitud declinada</span>";
    }
    else
    {
      $msg = "<span style='color: red'>Ocurrió un error al aprobar la solicitud</span>";
    }
    
    // TODO: enviar correo de notificacion al usuario
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
        echo '<a class="active" href="mensajes.php">Mensajes</a>';
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


<?php
  echo "<h3>$msg</h3>";
  $solicitudes = get_solicitudes_pendientes();
  if (mysqli_num_rows($solicitudes))
  {
    $html = "";

    while ($row = mysqli_fetch_array($solicitudes))
    {
      // Crear un formulario para cada solicitud con la opcion de Aceptar/Declinar
      $html .= "<form class='w3-container striped' action='mensajes.php' method='post' style='width: 50%'";
      $html .= "<label>Usuario:</label>";
      $html .= "<input class='w3-input' type='text' name='usuario' value='" . $row['usuario'] . "' readonly/>";
      $html .= "<label>Tipo:</label>";
      $html .= "<input class='w3-input' type='text' name='tipo' value='" . $row['tipo'] . "' readonly/>";

      if ($row['tipo'] == 'equipo')
      {
        $html .= "<label>Nombre:</label>";
        $html .= "<input class='w3-input' type='text' name='equipos_nombre' value='" . $row['equipos_nombre'] . "' readonly/>";
      }
      else if ($row['tipo'] == 'libro')
      {
        $html .= "<label>ID Libro:</label>";
        $html .= "<input class='w3-input' type='text' name='libro_id' value='" . $row['libros_id'] . "' readonly/>";
      }
      else if ($row['tipo'] == 'sala')
      {
        $html .= "<label>ID Sala:</label>";
        $html .= "<input class='w3-input' type='text' name='sala_id' value='" . $row['sala_id'] . "' readonly/>";
      }


      $html .= "<label>Fecha de prestamo:</label>";
      $html .= "<input class='w3-input' type='text' name='fecha_prestamo' value='" . $row['fecha_prestamo'] . "' readonly/>";

      $html .= "<label>Fecha de vencimiento:</label><br>";
      $html .= '<input id="fecha" name="fechaVencimiento" type="datetime-local" class="form-control picker" required>';
      $html .= "<br><br>";

      // Mostrar "Reporte cada" sólo si no es una sala
      if ($row['tipo'] != 'sala')
      {
        $html .= "<label>Reporte cada (minutos):</label><br>";
        $html .= "<input class='w3-input' type='number' name='reporteCada'/><br>";
      }

      // Si el admin quiere Declinar esta solicitud, aparece un nuevo cuadro de texto donde debe comentar la razon
      if (isset($_POST['decision'], $_POST['solicitud']) && $_POST['decision'] == 'Declinar' && $_POST['solicitud'] == $row['id'])
      {
        $html .= "<label style='color: red'>Razón de declinación:</label><br>";
        $html .= "<input class='w3-input' type='text' name='comentario' required/><br>";
      }

      // Poner un hidden field que identifique cual es la solicitud se esta aprobando/declinando. Permite saber en cual form se hizo click.
      $html .= "<input type='hidden' name='solicitud' value='". $row['id'] . "' required/>";

      $html .= "<input type='submit' name='decision' value='Aprobar'/>";
      $html .= "&nbsp;&nbsp;&nbsp;";
      $html .= "<input type='submit' name='decision' value='Declinar'/>";
      $html .= "</form><br><br>";
    }

    echo $html;
  }
  else
  {
    echo "<h3>No hay solicitudes pendientes.<h3>";
  }
?>

<script>
  $(function () {
    $(".striped:odd").css("background-color", "gray");
    $(".picker").flatpickr({enableTime: true, dateFormat:"Y-m-d H:i", defaultDate: new Date()});
  });
</script>
</body>
</html>