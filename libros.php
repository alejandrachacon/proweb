<?php
session_start();


 include_once dirname(__FILE__) . "/libros/libros_busqueda.php";

if ($_SERVER['REQUEST_METHOD']=='POST'){


      $titulo = $editorial = $autor = $disponibles ="";


        if(isset($_POST['titulo'])){ 

           $titulo = $_POST['titulo'];

        }

        if(isset($_POST['editorial'])){

                   $editorial = $_POST['editorial'];

        }

        if(isset($_POST['autor'])){

                       $autor= $_POST['autor'];

          }

         if(isset($_POST['disponibles'])){

                        $disponibles = $_POST['disponibles'];

          }
 

         $libros = fullsearchl($titulo,$autor,$editorial,$disponibles);
  
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
  <a class="active" href="libros.php">Libros</a>
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
   	
    
<form method="get" class="w3-container" action="libros.php">
    <input type="text" name="search_term" title="Search…" value="">
    <input type="submit" name="search" title="Busque ya!" value="Buscar" class="searchbutton" />
    <a href="busquedaAvanzada.php?tipo=libro"> Búsqueda Avanzada </a>
</form>

<br>

  <table class="w3-table-all w3-centered">
  <tr>
    <th>Imagen</th>
    <th>Titulo</th>
    <th>Editorial</th>
    <th>Disponibles</th>
  </tr>
  <?php
    include_once dirname(__FILE__) . "/libros/libros_crud.php";
    include_once dirname(__FILE__) . "/libros/libros_busqueda.php";
    include_once dirname(__FILE__) . "/solicitudes/solicitudes_crud.php";
    include_once dirname(__FILE__) . "/libros/libros_busqueda.php";
   

   // $libros = null;
    $html = "";
    if(isset($_GET['search_term']) && isset($_GET['search']))
    { 

        $libros = searchl($_GET['search_term']);


        if(!$libros){

              $html .= "<br> <h1> No se encontraron coincidencias</h1> ";
              $libros = get_libros();

        }

    }
    else{

      if($_SERVER['REQUEST_METHOD']!='POST')
      {
          $libros = get_libros();
      }

         // buscar todos los libros que al menos tengan 1 disponible

    }


    if ($libros)
    {
      while($row = mysqli_fetch_array($libros)) // Tomar cada fila del resultado y mostrarlo como una fila de la tabla
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
        $html .= "<td>" . $row['titulo'] . "</td>";
        $html .= "<td>" . $row['editorial'] . "</td>";

        if ($row['disponibles'] > 0)
        {
          $html .= "<td>" . $row['disponibles'] . "</td>";
        }
        else
        {
          $html .= "<td>";
          $libros = get_libro_mascercano_vencer($row['isbn']);
          if ($libros)
          {
            $html .= "Disponible: " . $equipo['fecha_vencimiento'];
          }
          else
          {
            $html .= "0";
          }
          $html .= "</td>";
        }
        if (isset($_SESSION['rol']) && $row['disponibles'] > 0) // Solo mostrar el botón de solicitar cuando el usuario tiene sesion iniciada
        {
          $html .= "<td>" . "<a href='libros/solicitar.php?isbn=" . $row['isbn'] . "&titulo=" . $row['titulo'] . "'>Solicitar</a>" . "</td>";
        }
        else
        {
          $html .= "<td></td>";
        }

        if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin') // Solo mostrar botón de actualizar/eliminar al admin
        {
          $html .= "<td>" . '<a href="actualizar.php?tipo=libro&isbn=' . $row['isbn'] . '">Actualizar</a>' . "</td>";
          $html .= "<td>" . '<a href="eliminar.php?tipo=libro&isbn=' . $row['isbn'] . '">Eliminar</a>' . "</td>";
        }
        
        $html .= "</tr>";
      }
    }

    echo $html;
  ?>  
  </table>
  <br>

</div>
  <?php
    if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin')
    {
      echo '<a href="agregar.php?tipo=libro">Agregar</a>';
    }
  ?>

</body>
</html>