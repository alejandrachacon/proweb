<?php
session_start();




  if ($_SERVER['REQUEST_METHOD']=='POST'){

  

      $nombre=$fabricante="";

       if(isset($_POST['nombre'])){

          $nombre = $_POST['nombre'];

          if(isset($_POST['fabricante'])){

            $fabricante = $_POST['fabricante'];

            if (isset($_POST['disponibles'])){

              $disponibles = $_POST['disponibles']; 
                
            }

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
        echo '<a href="reportes.php">Reportes</a>';
    }
  ?>
  <a href="eventos.php">Eventos</a>
  <a class="active" href="salas.php">Salas</a>
  <a href="equipos.php">Equipos</a>
  <a href="libros.php">Libros</a>
  <?php
		if (isset($_SESSION['rol']))
		{
			echo '<a href="logout.php">Log out</a>';
		}
	?>
</div>
<div class="w3-container" >

<br>
<br>


<form method="get" class="w3-container" action="salas.php">
    <input type="text" name="search_term" title="Search…" value="">
    <input type="submit" name="search" title="Busque ya!" value="Buscar" class="searchbutton" />
</form>

<br>

  <table class="w3-table-all w3-centered">
  <tr>
    <th>Sala</th>
    <th>Disponible</th>
  </tr>
  <?php
    include_once dirname(__FILE__) . "/salas/salas_crud.php";
    include_once dirname(__FILE__) . "/solicitudes/solicitudes_crud.php";
    include_once dirname(__FILE__) . "/salas/salas_busqueda.php";


    $html = "";
    $salas = null;
    if(isset($_GET['search_term']) && isset($_GET['search']))
    {

        //salas que coincidan con el parametro a buscar
        $salas = searche($_GET['search_term']);


        if(!$salas){

              $html .= "<br> <h1> ".$salas." </h1> ";
              $salas = get_salas();

        }

    }
    else{

        $salas = get_salas(); // buscar todos los salas que al menos tengan 1 disponible

    }

    if ($salas)
    {
      while($row = mysqli_fetch_array($salas)) // Tomar cada fila del resultado y mostrarlo como una fila de la tabla
      {
        $html .= "<tr>";
        $html .= "<td>" . $row['nombre'] . "</td>";
        if ($row['disponible'] == 1)
        {
          $html .= "<td>Disponible</td>";
        }
        else if ($row['disponible'] == 0)
        {
          $html .= "<td>";
          $sala = get_sala_mascercano_vencer($row['nombre']);
          if ($sala)
          {
            $html .= "Disponible: " . $sala['fecha_vencimiento'];
          }
          else
          {
            $html .= "0";
          }
          $html .= "</td>";
        }
        if (isset($_SESSION['rol']) && $row['disponible'] > 0) // Solo mostrar el botón de solicitar cuando el usuario tiene sesion iniciada
        {
          $html .= "<td>" . "<a href='solicitar.php?tipo=sala&nombre=" . $row['nombre'] . "'>Solicitar</a>" . "</td>";
        }
        else
        {
          $html .= "<td></td>";
        }
        
        if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin') // Solo mostrar botón de actualizar/eliminar al admin
        {
          // No tiene sentido actualizar/eliminar una sala.
          //$html .= "<td>" . '<a href="actualizar.php?tipo=sala&nombre=' . $row['nombre'] . '">Actualizar</a>' . "</td>";
          //$html .= "<td>" . '<a href="eliminar.php?tipo=sala&nombre=' . $row['nombre'] . '">Eliminar</a>' . "</td>";
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
      // No tiene sentido agregar una sala
      echo '<a href="agregar.php?tipo=sala">Agregar</a>';
    }
  ?>
  <br><br>
  
</div>
</body>
</html>