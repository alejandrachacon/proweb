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
<div class="w3-container" >
  <table class="w3-table-all w3-centered">
  <tr>
    <th>Imagen</th>
    <th>Fabricante</th>
    <th>Referencia</th>
    <th>Disponibles</th>
  </tr>
  <?php
    include_once dirname(__FILE__) . "/equipos/equipos_crud.php";
    include_once dirname(__FILE__) . "/solicitudes/solicitudes_crud.php";
    $html = "";
    $equipos = get_equipos(); // buscar todos los equipos que al menos tengan 1 disponible
    if ($equipos)
    {
      while($row = mysqli_fetch_array($equipos)) // Tomar cada fila del resultado y mostrarlo como una fila de la tabla
      {
        $html .= "<tr>";
        $html .= "<td>";
        if ($row['url_imagen'] != null) // Si el equipo tiene una imagen, mostrarla
        {
          $html .= "<img src='" . $row['url_imagen'] . "' width='20%'/>";
        }
        else // Si el equipo no tiene imagen, se utiliza un placeholder
        {
          $html .= "<img src='http://placehold.it/350x150' width='20%'/>";
        }
        $html .= "</td>";
        $html .= "<td>" . $row['fabricante'] . "</td>";
        $html .= "<td>" . $row['nombre'] . "</td>";
        if ($row['disponibles'] > 0)
        {
          $html .= "<td>" . $row['disponibles'] . "</td>";
        }
        else
        {
          $html .= "<td>";
          $equipo = get_equipo_mascercano_vencer($row['nombre']);
          if ($equipo)
          {
            $html .= "Disponible: " . $equipo['fecha_vencimiento'];
          }
          else
          {
            $html .= "0";
          }
          $html .= "</td>";
        }
        if (isset($_SESSION['rol'])) // Solo mostrar el botón de solicitar cuando el usuario tiene sesion iniciada
        {
          $html .= "<td>" . "<a href='equipos/solicitar.php?fabricante=" . $row['fabricante'] . "&nombre=" . $row['nombre'] . "'>Solicitar</a>" . "</td>";
        }
        
        if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin') // Solo mostrar botón de actualizar/eliminar al admin
        {
          $html .= "<td>" . '<a href="equipos/actualizar.php">Actualizar</a>' . "</td>";
          $html .= "<td>" . '<a href="equipos/eliminar.php">Eliminar</a>' . "</td>";
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
      echo '<a href="agregar.php?tipo=equipo">Agregar</a>';
    }
  ?>
  
</div>
</body>
</html>