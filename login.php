<?php
session_start();
require_once 'conexion.php';

class Autenticacion {
    private $pdo;

    public function __construct() {
        $conexion = new Conexion();
        $this->pdo = $conexion->conectar();
    }

    public function login($correo, $contraseña, $id_rol) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE correo = ? AND id_rol = ?");
        $stmt->execute([$correo, $id_rol]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contraseña, $usuario['Contraseña'])) {
            return $usuario;
        }

        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['Correo'];
    $contraseña = $_POST['Password'];
    $id_rol = $_POST['id_rol'];

    $auth = new Autenticacion();
    $usuario = $auth->login($correo, $contraseña, $id_rol);

    if ($usuario) {
        $_SESSION['usuario'] = $usuario['Nombre'];
        $_SESSION['id_usuario'] = $usuario['IdUsuario'];
        $_SESSION['id_rol'] = $usuario['id_rol'];

        echo "✅ Bienvenido, " . $usuario['Nombre'];
    } else {
        echo "❌ Correo, contraseña o rol incorrecto.";
    }
}
?>