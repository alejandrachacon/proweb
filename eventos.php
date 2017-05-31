<?php
session_start();
date_default_timezone_set('America/Bogota');
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
    <th>Nombre</th>
    <th>Lugar</th>
    <th>Fecha de Inicio</th>
    <th>Fecha de Fin</th>
  </tr>
  <?php
    include_once dirname(__FILE__) . "/eventos/eventos_crud.php";


    $html = "";

        $eventos = get_eventos(); // buscar todos los eventos vigentes
      


    if ($eventos)
    {
      while($row = mysqli_fetch_array($eventos)) // Tomar cada fila del resultado y mostrarlo como una fila de la tabla
      {
        $html .= "<tr>";
        $html .= "<td>" . $row['nombre'] . "</td>";
        $html .= "<td>" . $row['lugar'] . "</td>";
        $html .= "<td>" . $row['fechainicio'] . "</td>";
        $html .= "<td>" . $row['fechafin'] . "</td>";
     //   $html .= "<td>" .strftime("%Y %m %d, %X %Z",mktime()). "</td>";
      //date_diff(datetime1,datetime2,absolute);
        //&& date_diff(mktime(),strtotime($row['fechainicio']),'absolute')

        if (isset($_SESSION['rol']) ) // Solo mostrar el botón de solicitar cuando el usuario tiene sesion iniciada
        {
          $html .= "<td>" . "<a href='suscribir.php?id=" . $row['id'] . "'>Más información</a>" . "</td>";
        }
        else
        {
          $html .= "<td></td>";
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