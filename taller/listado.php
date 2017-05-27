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
</head>
<body>


<div class="topnav">
  <a class="active" href="index.php">Inicio</a>

</div>
<div style="padding-left:16px">
	<br>
	<br>
   	<div class="w3-card-4">
	  <div class="w3-container w3-green">
	    <h2>Listado de usuarios</h2>

      <?php
          
          if($_SERVER['REQUEST_METHOD'] === 'POST') {
                  $value = $_POST['userow'];
                  $id = 1;

                  updater($value, $id);
              }


           include_once dirname(__FILE__) . '/config.php';
            $conn = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
      // Check connection
            if ($conn->connect_error) {

                die("Connection failed: " . $conn->connect_error);
            } 

            $sql = "SELECT nombreusuario,rol FROM Usuario";
            $result = mysqli_query($conn, $sql);


            if (mysqli_num_rows($result) > 0) {
                echo "<table><tr><th>Nombre</th><th>Rol</th><th>Editar</th></tr>";
                // output data of each row
                while($row = mysqli_fetch_assoc($result)) {
                  $userow = $row["nombreusuario"];
                    echo "<tr><td>".$row["nombreusuario"]."</td><td>".$row["rol"]."</td><td>

                      <form method=\"post\" action=\"<?php echo
                                $_SERVER["PHP_SELF"];?>\">

                          <input type = \"submit\"  class=\"w3-btn w3-cyan\" name= \"userow\"  value = \"Hacer admin \">
                      </form>
                      </td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
            mysqli_close($conn);

      ?>  


	  </div>
	 
    </div>
</div>

</body>
</html>