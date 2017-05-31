<?php
include_once dirname(__FILE__) . "/../config.php";

date_default_timezone_set('America/Bogota');

$con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

if (mysqli_connect_errno())
{
  echo "Error en la conexión: " . mysqli_connect_error();
  exit();
}

// SELECT de todas las salas existentes
function get_salas()
{
  global $con;
  $sql = "SELECT nombre, disponible FROM sala WHERE disponible >= 0";
  
  $result = mysqli_query($con, $sql);

  //mysqli_close($con);
  return $result;
}

// SELECT de todas las salas existentes
function get_salas_disp()
{
  global $con;
  $sql = "SELECT nombre, disponible FROM sala WHERE disponible > 0";
  
  $result = mysqli_query($con, $sql);

  //mysqli_close($con);
  return $result;
}

// Selecciona la primera sala disponible con el nombre especificados
// Return: nombre de sala o false si no hay ninguna disponible
function get_sala_disponible($nombre)
{
  global $con;
  $sql = "SELECT nombre FROM sala WHERE disponible > 0 AND nombre = '$nombre'";
  
  $result = mysqli_query($con, $sql);
  if (!$result)
  {
    return false;
  }
  // Sino encuentra nada retornar false
  if (mysqli_num_rows($result) <= 0)
  {
    return false;
  }

  $row = mysqli_fetch_array($result); // Coger el primer resultado

  return $row;
}

// Registrar una solicitud para pedir sala
// TODO: mover a solicitudes/solicitudes_crud.php
function solicitar_sala($usuario, $nombre, $fechaPrestamo)
{
  global $con;
  $sala = get_sala_disponible($nombre);

  // Si no encontró ninguna sala disponible, retornar
  if (!$sala)
  {
    return false;
  }

  $fechaPrestamo .= ":00" ;
  $sql = "INSERT INTO `solicitudes`(`usuario`, `sala_nombre`, `tipo`, `fecha_prestamo`,`estado`) VALUES ('$usuario', '" . $nombre . "', 'sala', '$fechaPrestamo', 'solicitado')";
  
  if (!mysqli_query($con, $sql))
  {
    return false;
  }

  return true;

}

function crear_sala($nombre)
{
  global $con;
  $sql = "INSERT INTO `sala`(`nombre`, `disponible`) VALUES ('$nombre', 1)";
  
  if (!mysqli_query($con, $sql))
  {
    return false;
  }

  return true;
}

function actualizar_sala($nombre, $disponible)
{
  global $con;
  $sql = "UPDATE `sala` SET `disponible`=$disponible WHERE `nombre`='$nombre'";
  
  if (!mysqli_query($con, $sql))
  {
    return false;
  }

  return true;
}


function buscar_sala($nombre)
{
  global $con;
  $sql = "SELECT * FROM sala WHERE nombre='$nombre' AND disponible >= 0";
  
  $result = mysqli_query($con, $sql);
  if (!$result)
  {
    return false;
  }

  $row = mysqli_fetch_array($result);
  return $row;
}

function eliminar_sala($nombre)
{
  global $con;
  $sql = "UPDATE sala SET `disponible`=-1 WHERE nombre='$nombre' LIMIT 1";

  $result = mysqli_query($con, $sql);
  if (!$result)
  {
    return false;
  }

  return true;
}


?>