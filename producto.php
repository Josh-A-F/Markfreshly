<?php
require_once 'conexion.php';
class Producto {
    private $pdo;
    private $Nombre_Producto;
    private $Descripcion_Producto;
    private $Precio_Unitario;
    private $Stock;
    private $Fecha_Creacion;  
    private $Imagen_Producto;
    private $Categorias_idCategorias;
    private $Subastas_idSubastas;
    private $Carrito_Compras_idCarrito_Compras;
    private $Favoritos_idFavoritos;
    private $Detalle_Orden_idDetalle_Orden;
    private $Inventario_idInventario;

    public function __construct($pdo) {
        if (!$pdo) {
            die("Conexión fallida en Producto.");
        }
        $this->pdo = $pdo;
    }
    public function setNombre_Producto($Nombre_Producto) { $this->Nombre_Producto = $Nombre_Producto; }
    public function setDescripcion_Producto($Descripcion_Producto) { $this->Descripcion_Producto = $Descripcion_Producto; }
    public function setPrecio_Unitario($Precio_Unitario) { $this->Precio_Unitario = $Precio_Unitario; }
    public function setStock($Stock) { $this->Stock = $Stock; }
    public function setFecha_Creacion($Fecha_Creacion) { $this->Fecha_Creacion = $Fecha_Creacion; }
    public function setImagen_Producto($Imagen_Producto) { $this->Imagen_Producto = $Imagen_Producto; }
    public function setCategorias_idCategorias($Categorias_idCategorias) { $this->Categorias_idCategorias = $Categorias_idCategorias; }
    public function setSubastas_idSubastas($Subastas_idSubastas) { $this->Subastas_idSubastas = $Subastas_idSubastas; }
    public function setCarrito_Compras_idCarrito_Compras($Carrito_Compras_idCarrito_Compras) { $this->Carrito_Compras_idCarrito_Compras = $Carrito_Compras_idCarrito_Compras; }
    public function setFavoritos_idFavoritos($Favoritos_idFavoritos) { $this->Favoritos_idFavoritos = $Favoritos_idFavoritos; }
    public function setDetalle_Orden_idDetalle_Orden($Detalle_Orden_idDetalle_Orden) { $this->Detalle_Orden_idDetalle_Orden = $Detalle_Orden_idDetalle_Orden; }
    public function setInventario_idInventario($Inventario_idInventario) { $this->Inventario_idInventario = $Inventario_idInventario; }

    public function registrar() {
        $sql = "INSERT INTO productos (Nombre_Producto, Descripcion_Producto, Precio_Unitario, Stock, Imagen_Producto, Fecha_Creacion, Categorias_idCategorias, Subastas_idSubastas, Carrito_Compras_idCarrito_Compras, Favoritos_idFavoritos, Detalle_Orden_idDetalle_Orden, Inventario_idInventario)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute([
            $this->Nombre_Producto,
            $this->Descripcion_Producto,
            $this->Precio_Unitario,
            $this->Stock,
            $this->Imagen_Producto,
            $this->Fecha_Creacion,  // La fecha es la actual
            $this->Categorias_idCategorias,
            $this->Subastas_idSubastas,
            $this->Carrito_Compras_idCarrito_Compras,
            $this->Favoritos_idFavoritos,
            $this->Detalle_Orden_idDetalle_Orden,
            $this->Inventario_idInventario
        ])) {
            echo "<script>
                    alert('✅ Producto agregado exitosamente.');
                    window.location.href = 'productos.php';
                  </script>";
            exit();
        } else {
            echo "<script>alert('❌ Error al agregar el producto. Intenta de nuevo.'); window.history.back();</script>";
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conexion = new Conexion();
        $pdo = $conexion->conectar();

        $producto = new Producto($pdo);

        $producto->setNombre_Producto($_POST['Nombre_Producto'] ?? '');
        $producto->setDescripcion_Producto($_POST['Descripcion_Producto'] ?? '');
        $producto->setPrecio_Unitario($_POST['Precio_Unitario'] ?? '');
        $producto->setStock($_POST['Stock'] ?? '');
        $producto->setFecha_Creacion(date('Y-m-d H:i:s'));  // Fecha actual
        $producto->setCategorias_idCategorias($_POST['Categorias_idCategorias'] ?? 1);
        $producto->setSubastas_idSubastas($_POST['Subastas_idSubastas'] ?? 1);
        $producto->setCarrito_Compras_idCarrito_Compras($_POST['Carrito_Compras_idCarrito_Compras'] ?? 1);
        $producto->setFavoritos_idFavoritos($_POST['Favoritos_idFavoritos'] ?? 1);
        $producto->setDetalle_Orden_idDetalle_Orden($_POST['Detalle_Orden_idDetalle_Orden'] ?? 1);
        $producto->setInventario_idInventario($_POST['Inventario_idInventario'] ?? 1);

        if (isset($_FILES['Imagen_Producto']) && $_FILES['Imagen_Producto']['error'] == 0) {
            $producto->setImagen_Producto(file_get_contents($_FILES['Imagen_Producto']['tmp_name']));
        } else {
            echo "<script>alert('❌ Error al subir la imagen.'); window.history.back();</script>";
            exit();
        }

        $producto->registrar();
    } catch (Exception $e) {
        echo "<script>alert('⚠️ Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>
