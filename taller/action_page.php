<?php
            if(isset($_POST['username'])){
        $nomUsr = $_POST['username'];
        //setcookie("usuario","",time()+1200);
      }
      if(isset($_POST['email'])){
        $email = $_POST['email'];
       // setcookie("email","",time()+1200);
      }
      if (isset($_POST['cc'])) {
        # code...

        $cedula = $_POST['cc'];
       // setcookie("cedula","",time()+1200);
      }
        
          $contrasena = md5($_POST["password"]);
          $user = $_POST["username"];
          $rolA = "admin";
          $rolB = "usuario";
          $ced = $_POST['cc'];

          include_once dirname(__FILE__) . '/config.php';
           $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

           $sql = "SELECT id FROM Usuario";
           $result = mysqli_query($con, $sql);

                if ( $result->num_rows == 0) {
                  # code...
                   $sql = "INSERT INTO Usuario (nombreusuario,contrasena,rol,cedula) VALUES ('$user','$contrasena','$rolA','$ced')";


                       $result = mysqli_query($con, $sql);

                    if (!$result) {
                     echo "Error: " . mysqli_error($con);

                                     /*                             $query = "SHOW FIELDS FROM Usuario";
                                            $result = mysqli_query($con,$query ) or die( mysqli_error() );
                                            while( $row = mysqli_fetch_array( $result ) ) {
                                                     echo $row['Field'] . "<br />";
                                            }
                                            mysqli_free_result( $result ); */ 
                   }
                    
                }
                else{
                  if ( $result->num_rows > 0) {
                  # code...
                   $sql = "INSERT INTO Usuario (nombreusuario,contrasena,rol,cedula) VALUES ('$user','$contrasena','$rolB','$ced')";


                       $result = mysqli_query($con, $sql);

                    if (!$result) {

                     echo "Error: " . mysqli_error($con);

                     }
                   

                }
            }

                  mysqli_close($con);

                  header("Location: http://localhost/taller/perfil.php");

      ?>