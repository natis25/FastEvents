<!-- services/sesion/controllers/SesionController.php -->
<?php
include_once('../model/sesionModel.php');
session_start();

class SesionController
{
  public function iniciarSesion($email, $password, $tipoUsuario)
  {
    // Validar credenciales
    $sesionModel = new SesionModel();
    $usuario = $sesionModel->validarUsuario($email, $password, $tipoUsuario);

    if ($usuario) {
      // Usuario autenticado
      $_SESSION['usuario'] = $usuario;
      $_SESSION['tipo'] = $tipoUsuario;

      // Mostrar el mensaje según el tipo de usuario
      if ($tipoUsuario == 'cliente') {
        // Redirige al archivo mostrarCuentas.php con el ID del cliente
        header("Location: ../../participantes/inicioCliente.php?id=" . $_SESSION['usuario']['id']);
        exit();
      } else {
        // Redirige al archivo mostrarCuentas.php con el ID del organizador
        header("Location: ../../manejoCuentas/mostrarOrganizador.php?id=" . $_SESSION['usuario']['id']);
        exit();
      }
    } else {
      // Credenciales incorrectas
      echo "Credenciales incorrectas.";
    }
  }
}
?>