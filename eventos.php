<?php
session_start();
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
  <a class="active" href="eventos.php">Eventos</a>
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

<div style="padding-left:16px">
	<br>
	<br>
<br>

  <table class="w3-table-all w3-centered">
  <tr>
    <th>Sala</th>
    <th>Fecha de Inicio</th>
    <th>Fecha de Fin</th>
  </tr>
  <?php
    include_once dirname(__FILE__) . "/eventos/eventos_crud.php";


    $html = "";

        $eventos = get_eventos(); // buscar todos los eventos vigentes
      


    }

    if ($eventos)
    {
      while($row = mysqli_fetch_array($eventos)) // Tomar cada fila del resultado y mostrarlo como una fila de la tabla
      {
        $html .= "<tr>";
        $html .= "<td>" . $row['sala_nombre'] . "</td>";
        $html .= "<td>" . $row['fechainicio'] . "</td>";
        $html .= "<td>" . $row['fechafin'] . "</td>";
      
        if (isset($_SESSION['rol']) && $row['disponibles'] > 0) // Solo mostrar el botón de solicitar cuando el usuario tiene sesion iniciada
        {
          $html .= "<td>" . "<a href='solicitar.php?tipo=equipo&fabricante=" . $row['fabricante'] . "&nombre=" . $row['nombre'] . "'>Solicitar</a>" . "</td>";
        }
        else
        {
          $html .= "<td></td>";
        }
        
        if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin') // Solo mostrar botón de actualizar/eliminar al admin
        {
          $html .= "<td>" . '<a href="actualizar.php?tipo=evento&nombre=' . $row['sala_nombre'] . '">Actualizar</a>' . "</td>";
          $html .= "<td>" . '<a href="eliminar.php?tipo=evento&nombre=' . $row['sala_nombre'] . '">Eliminar</a>' . "</td>";
        }
        $html .= "</tr>";
      }
    }

    echo $html;
  ?>  
  </table>
  <br>

  <?php
    if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin')
    {
      echo '<a href="agregar.php?tipo=evento">Agregar</a>';
    }
  ?>
  <br><br>
</div>

</body>
</html>