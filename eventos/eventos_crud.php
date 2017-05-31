<?php
include_once dirname(__FILE__) . "/../config.php";

date_default_timezone_set('America/Bogota');

$con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

if (mysqli_connect_errno())
{
  echo "Error en la conexión: " . mysqli_connect_error();
  exit();
}

// SELECT de todos los eventos
function get_eventos()
{
  global $con;
  $sql = "SELECT id, fechainicio, fechafin, lugar, sala_nombre, nombre,informacion FROM eventos ";
  
  $result = mysqli_query($con, $sql);

  return $result;
}

function crear_evento($id, $fechainicio, $fechafin, $lugar, $sala_nombre,$nombre,$informacion)
{
  global $con;
  $sql = "INSERT INTO `eventos`(`id`, `fechainicio`, `fechafin`, `lugar`, `sala_nombre`, `nombre`, `informacion`) VALUES ('$id', '$fechainicio', '$fechafin', '$lugar', '$sala_nombre', '$nombre', '$informacion')";
  
  if (!mysqli_query($con, $sql))
  {
    return false;
  }

  return true;
}

function actualizar_evento($id, $fechainicio, $fechafin, $lugar, $sala_nombre,$nombre,$informacion)
{
  global $con;
  $sql = "UPDATE `eventos` SET `fechainicio`='$fechainicio',`fechafin`='$fechafin',`lugar`=$lugar,`sala_nombre`=$sala_nombre,`nombre`=$nombre
  ,`informacion`=$informacion WHERE `id`='$id'";

  if (!mysqli_query($con, $sql))
  {
    return false;
  }

  return true;
}

function buscar_evento($id)
{
  global $con;
  $sql = "SELECT id, fechainicio, fechafin, lugar, sala_nombre, nombre,informacion FROM eventos WHERE id='$id' ";
  
  $result = mysqli_query($con, $sql);
  if (!$result)
  {
    return false;
  }

  $row = mysqli_fetch_array($result);
  return $row;
}

/*function eliminar_evento($isbn)
{
  global $con;
  $sql = "UPDATE eventos SET total=-1 WHERE isbn='$isbn' LIMIT 1";

  return mysqli_query($con, $sql);
}
*/
?>