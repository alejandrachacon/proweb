<?php
include_once dirname(__FILE__) . "/config.php";
$con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

if (mysqli_connect_errno())
{
  echo "Error en la conexión: " . mysqli_connect_error();
  exit();
}

function login($user, $password)
{
  $sql = "SELECT usuario, email, rol FROM usuarios WHERE usuario = '$user' AND password = '$password'";
  global $con;
  $result = mysqli_query($con, $sql);
  
  if (mysqli_num_rows($result) <= 0)
  {
    return false;
  }
  
  $row = mysqli_fetch_array($result);
  return $row;

  mysqli_close($con);
}

function register($user,$password,$email)
{
  global $con;
    $sql = "INSERT INTO usuarios (usuario,email,password,rol) VALUES ('$user','$email','$password','usuario')";
    $result = mysqli_query($con,$sql);
    $row = login($user,$password);
    return $row;
}

?>