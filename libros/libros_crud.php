<?php
include_once dirname(__FILE__) . "/../config.php";

date_default_timezone_set('America/Bogota');

$con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

if (mysqli_connect_errno())
{
  echo "Error en la conexión: " . mysqli_connect_error();
  exit();
}

// SELECT de todos los libros
function get_libros()
{
  global $con;
  $sql = "SELECT * FROM libros";
  
  $result = mysqli_query($con, $sql);

  return $result;
}

function crear_libro($titulo, $autor, $edicion, $editorial, $paginas, $isbn, $copias, $imagen)
{
  global $con;
  $sql = "INSERT INTO `libros`(`isbn`, `titulo`, `autor`, `editorial`, `paginas`, `disponibles`, `total`, `url_imagen`) VALUES ('$isbn', '$titulo', '$autor', '$editorial', '$paginas', '$copias', '$copias', '$imagen')";
  
  if (!mysqli_query($con, $sql))
  {
    return false;
  }

  return true;
}

?>