<?php
  
      include_once dirname(__FILE__) . '/config.php';
       $conn = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
                          
            if ($conn->connect_error) {

               die("Connection failed: " . $conn->connect_error);
            } 

           $sql = "UPDATE Usuario SET rol='admin' WHERE nombreusuario=".$GLOBALS['userow'];

           $result = mysqli_query($conn, $sql);

            if (!$result) {

             echo "Error: " . mysqli_error($conn);

            }
            else{
              echo 'Super bien?';
            }
            mysqli_close($con);

            header("Location: http://localhost/taller/listado.php");

?>