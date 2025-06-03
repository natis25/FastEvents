<!-- services/sesion/model/SesionModel.php -->
<?php

class SesionModel
{
  private $conn;

  // opcion que funciona
  // public function __construct() 
  // {
  //   // Conexión a la base de datos
  //   $servidor = "localhost";
  //   $usuario = "root";
  //   $contrasena = "";
  //   $basedatos = "usuario";

  //   $conexion = new mysqli($servidor, $usuario, $contrasena, $basedatos);

  //   if ($conexion->connect_error) {
  //       die("Error de conexión: " . $conexion->connect_error);
  //   }
  //   $this->conn = $conexion;
  // }

  // Alternativa
  public function __construct()
  {
    include_once('../conexion.php'); // Incluir el archivo de conexión
    global $conexion;        // Usamos la variable como fue definida
    $this->conn = $conexion; // Guardamos en el atributo
  }

  public function validarUsuario($email, $password, $tipoUsuario)
  {
    // Consultar la base de datos dependiendo del tipo de usuario
    if ($tipoUsuario == 'cliente') {
      $query = "SELECT * FROM cliente WHERE correo = ? AND contrasena = ?";
    } else {
      $query = "SELECT * FROM organizador WHERE correo = ? AND contrasena = ?";
    }

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $usuario = $result->fetch_assoc();

      // Mapear el ID correctamente sin importar el tipo
      $usuario['id'] = ($tipoUsuario == 'cliente')
        ? $usuario['id_cliente']
        : $usuario['id_organizador'];

      return $usuario;
    } {
      return false; // No se encontró el usuario
    }
  }
}

?>