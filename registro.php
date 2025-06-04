<?php
require_once 'conexion.php';

class Usuario {
    private $pdo;
    private $nombre;
    private $apellido;
    private $correo;
    private $direccion;
    private $celular;
    private $contraseña;
    private $id_rol;
    private $estado;

    public function __construct($pdo) {
        if (!$pdo) {
            die("Conexión fallida en Usuario.");
        }
        $this->pdo = $pdo;
    }

    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    public function setCorreo($correo) { $this->correo = $correo; }
    public function setDireccion($direccion) { $this->direccion = $direccion; }
    public function setCelular($celular) { $this->celular = $celular; }

    public function setContraseña($contraseña) {
        if (!empty($contraseña)) {
            $this->contraseña = password_hash($contraseña, PASSWORD_BCRYPT);
        } else {
            throw new Exception("La contraseña no puede estar vacía.");
        }
    }

    public function setIdRol($id_rol) { $this->id_rol = $id_rol; }
    public function setEstado($estado) { $this->estado = $estado; }

    public function registrar() {
        $sql = "INSERT INTO usuarios (nombre, apellido, correo, direccion, celular, contraseña, id_rol, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute([
            $this->nombre,
            $this->apellido,
            $this->correo,
            $this->direccion,
            $this->celular,
            $this->contraseña,
            $this->id_rol,
            $this->estado
        ])) {
            
            echo "<script>
                    alert('✅ Registro exitoso. Ahora puedes iniciar sesión.');
                    window.location.href = 'login.html';
                  </script>";
            exit();
        } else {
            echo "<script>alert('❌ Error en el registro. Intenta de nuevo.'); window.history.back();</script>";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario'])) {
    try {
        $conexion = new Conexion();
        $pdo = $conexion->conectar();

        $usuario = new Usuario($pdo);

        $usuario->setNombre($_POST['nombre'] ?? '');
        $usuario->setApellido($_POST['apellido'] ?? '');
        $usuario->setCorreo($_POST['correo'] ?? '');
        $usuario->setDireccion($_POST['direccion'] ?? '');
        $usuario->setCelular($_POST['celular'] ?? '');
        $usuario->setContraseña($_POST['contraseña'] ?? '');
        $usuario->setIdRol($_POST['id_rol'] ?? 1);
        $usuario->setEstado(1);

        $usuario->registrar();
    } catch (Exception $e) {
        echo "<script>alert('⚠️ Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>
