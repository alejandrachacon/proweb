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
  $sql = "SELECT isbn,titulo,autor,editorial,paginas,disponibles,total, url_imagen FROM libros WHERE total > 0";
  
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

function actualizar_libro($titulo, $autor, $editorial, $paginas, $isbn, $disponibles, $copias, $imagen)
{
  global $con;
  $sql = "UPDATE `libros` SET `titulo`='$titulo',`autor`='$autor',`editorial`='$editorial',`paginas`=$paginas,`disponibles`=$disponibles,`total`=$copias,`url_imagen`='$imagen' WHERE `isbn`='$isbn'";

  if (!mysqli_query($con, $sql))
  {
    return false;
  }

  return true;
}

function buscar_libro($isbn)
{
  global $con;
  $sql = "SELECT * FROM libros WHERE isbn='$isbn' AND total > 0";
  
  $result = mysqli_query($con, $sql);
  if (!$result)
  {
    return false;
  }

  $row = mysqli_fetch_array($result);
  return $row;
}

function eliminar_libro($isbn)
{
  global $con;
  $sql = "UPDATE libros SET total=-1 WHERE isbn='$isbn' LIMIT 1";

  return mysqli_query($con, $sql);
}

?>