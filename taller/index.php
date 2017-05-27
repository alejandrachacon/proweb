<?php
      if(isset($_POST['username'])){
        $nomUsr = $_POST['username'];
        setcookie("usuario",$nomUsr,time()+3200);
      }
      if(isset($_POST['email'])){
        $email = $_POST['email'];
        setcookie("email",$email,time()+3200);
      }
      if (isset($_POST['cc'])) {
        # code...

        $cedula = $_POST['cc'];
        setcookie("cedula",$cedula,time()+3200);
      }
      if (isset($_POST['password'])) {
        # code...

        $pass = $_POST['password'];
        setcookie("password",$pass,time()+3200);
      }
        
?>

<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
body {margin:0;}

.topnav {
  overflow: hidden;
  background-color: #333;
}

.topnav a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
    background-color: #4CAF50;
    color: white;
}
</style>
<?php
      include_once dirname(__FILE__) . '/config.php';
      $con=mysqli_connect(HOST_DB,USUARIO_DB,USUARIO_PASS);
      $sql="CREATE DATABASE IF NOT EXISTS miBD";
      if (mysqli_query($con,$sql)) {
      //echo "BIEN BD";
      }else {
      echo "Error en la creación " . mysqli_error($con);
      }
?>
</head>
<body>


<div class="topnav">
  <a class="active" href="index.php">Registro</a>
  <a  href="login.php">Login</a>

</div>

<?php
      include_once dirname(__FILE__) . '/config.php';
      $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
      $sql = "CREATE TABLE IF NOT EXISTS Usuario(
      id INT(10) AUTO_INCREMENT PRIMARY KEY,
      nombreusuario VARCHAR(50),
      contrasena VARCHAR(60),
      rol ENUM('admin','usuario'),
      cedula VARCHAR(20),
      UNIQUE(nombreusuario)
      )";
      if (mysqli_query($con, $sql)) {
      //echo "Tabla Usuario creada correctamente";
      } else {
      echo "Error en la creación " . mysqli_error($con);
      }
      mysqli_close($con);
?>

<div style="padding-left:16px">
  <br>
  <br>
    <div class="w3-card-4">
    <div class="w3-container w3-green">
      <h2>Ingrese a la plataforma</h2>

      <?php
          $errCC = $errMail = $errNom = "";

          if($_SERVER["REQUEST_METHOD"]=="POST"){

            if (!empty($_POST["username"])){

                if(!preg_match('/^[a-zA-Z\s]+$/', $_POST["username"])){

                $errNom = "<div class=error > <label class=\"w3-text-red\"><b>El nombre no tiene el formato esperado</b></label> </div>";
                }
            }
            else{
            $errNom = "<div class=error > <label class=\"w3-text-red\"><b>Ingrese un nombre</b></label> </div>";
            }
            if (!empty($_POST["email"])){
             
                if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                 $errMail = "<div class=error > <label class=\"w3-text-red\"><b>El email no tiene el formato esperado</b></label> </div>";
                }
              
            }
            else{
              $errMail = "<div class=error > <label class=\"w3-text-red\"><b>Ingrese un email </b></label> </div>";
            }
            if (!empty($_POST["cc"])){

              if(!is_numeric($_POST["cc"])){
                $errCC = "<div class=error > <label class=\"w3-text-red\"><b>la cedula no tiene el formato esperado</b></label> </div>";
              }
              include_once dirname(__FILE__) . '/config.php';
              $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
                  $sql = "SELECT cedula FROM Usuario WHERE cedula =".$_POST['cc'];
                  $result = mysqli_query($con, $sql);

                    if (mysqli_num_rows($result) > 0) {
                    //echo "Tabla Usuario creada correctamente";
                      $errCC = "<div class=error > <label class=\"w3-text-red\"><b>Ya existe un usuario con esta cedula</b></label> </div>";

                    } else {
                    echo "Error en la creación " . mysqli_error($con);
                    }
                    mysqli_close($con);
              
              
            }
            else{
              $errCC = "<div class=error > <label class=\"w3-text-red\"><b>Ingrese una cedula</b></label> </div>";
            }
          }
          
      ?>
    </div>
    <form method="post" class="w3-container" action="<?php echo
$_SERVER["PHP_SELF"];?>">
      <p>      
      <label class="w3-text-green"><b>Nombre</b></label>
      <input class="w3-input w3-border w3-sand" name="username" type="text" value="<?php echo (isset($nomUsr))?$nomUsr:'';?>" >
      <?php
        echo $errNom;
      ?>
      </p>
      <p>       
      <label class="w3-text-green"><b>Email</b></label>
      <input class="w3-input w3-border w3-sand" name="email" type="text" value="<?php echo (isset($email))?$email:'';?>" >
      </p>
      <?php
        echo $errMail;
      ?>
      <p>      
      <label class="w3-text-green"><b>Cedula</b></label>
      <input class="w3-input w3-border w3-sand" name="cc" type="text"
      value="<?php echo (isset($cedula))?$cedula:'';?>" >
      <?php
        echo $errCC;

        if (!empty($_POST["password"])) {
          # code...
        header("Location: http://localhost/taller/action_page.php");
        exit();
        }

      ?>
      </p>
      <p>       
      <label class="w3-text-green"><b>Contraseña</b></label>
      <input class="w3-input w3-border w3-sand" name="password" type="text">
      
      </p>
      <p>
      <button class="w3-btn w3-green" type="submit">Submit</button>
      </p>
    </form>
   </div>
</div>

</body>
</html>