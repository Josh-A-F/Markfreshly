<?php
require_once 'conexion.php';

class Usuario {
    private $pdo;
    private $Tipo_Documento;
    private $Numero_Documento;
    private $Nombre_Usuario;
    private $Apellido_Usuario;
    private $Correo;
    private $Celular;
    private $Contraseña;
    private $Fecha_Registro;
    private $id_rol;

    public function __construct($pdo) {
        if (!$pdo) {
            die("Conexión fallida en Usuario.");
        }
        $this->pdo = $pdo;
    }

    public function setTipo_Documento($Tipo_Documento) { $this->Tipo_Documento = $Tipo_Documento; }
    public function setNumero_Documento($Numero_Documento) { $this->Numero_Documento = $Numero_Documento; }
    public function setNombre_Usuario($Nombre_Usuario) { $this->Nombre_Usuario = $Nombre_Usuario; }
    public function setApellido_Usuario($Apellido_Usuario) { $this->Apellido_Usuario = $Apellido_Usuario; }
    public function setCorreo($correo) { $this->correo = $correo; }
    public function setCelular($celular) { $this->celular = $celular; }
    public function setFecha_Registro($Fecha_Registro) { $this->Fecha_Registro = $Fecha_Registro; }

    public function setContraseña($contraseña) {
        if (!empty($contraseña)) {
            $this->contraseña = password_hash($contraseña, PASSWORD_BCRYPT);
        } else {
            throw new Exception("La contraseña no puede estar vacía.");
        }
    }

    public function setIdRol($id_rol) { $this->id_rol = $id_rol; }

    public function registrar() {
        $sql = "INSERT INTO usuarios (Tipo_Documento, Numero_Documento, Nombre_Usuario, Apellido_Usuario, correo, celular, contraseña, fecha_Registro, id_rol)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute([

            $this->Tipo_Documento,
            $this->Numero_Documento,
            $this->Nombre_Usuario,
            $this->Apellido_Usuario,
            $this->Correo,
            $this->Celular,
            $this->Contraseña,
            $this->Fecha_Registro,
            $this->id_rol,

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


        $usuario->setTipo_Documento($_POST['tipo_documento'] ?? '');
        $usuario->setNumero_Documento($_POST['Numero_Documento'] ?? '');
        $usuario->setNombre_Usuario($_POST['Nombre_Usuario'] ?? '');
        $usuario->setApellido_Usuario($_POST['Apellido_Usuario'] ?? '');
        $usuario->setCorreo($_POST['Correo'] ?? '');
        $usuario->setCelular($_POST['Celular'] ?? '');
        $usuario->setContraseña($_POST['Contraseña'] ?? '');
        $usuario->setFecha_Registro($_POST['Fecha_Registro'] ?? 1);
        $usuario->setIdRol($_POST['id_rol'] ?? 1);

        $usuario->registrar();
    } catch (Exception $e) {
        echo "<script>alert('⚠️ Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>
