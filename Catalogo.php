<?php
require_once 'conexion.php';

class Producto {
    private $pdo;
    private $id;
    private $name;
    private $category;
    private $price;
    private $image;
    private $stock;

    public function __construct($pdo, $id = null, $name = null, $category = null, $price = null, $image = null, $stock = null) {
        if (!$pdo) {
            die("Conexión fallida en Product.");
        }
        $this->pdo = $pdo;
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->price = $price;
        $this->image = $image;
        $this->stock = $stock;
    }

    public function setId($id) { $this->id = $id; }
    public function setName($name) { $this->name = $name; }
    public function setCategory($category) { $this->category = $category; }
    public function setPrice($price) { $this->price = $price; }
    public function setImage($image) { $this->image = $image; }
    public function setStock($stock) { $this->stock = $stock; }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getCategory() { return $this->category; }
    public function getPrice() { return $this->price; }
    public function getImage() { return $this->image; }
    public function getStock() { return $this->stock; }

    public function getAllProducts() {
        $products = [];
        $stmt = $this->pdo->query("SELECT * FROM Productos");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $image = $row['Imagen_Producto'] ? 'data:image/jpeg;base64,' . base64_encode($row['Imagen_Producto']) : 'https://via.placeholder.com/150';
            $product = new Producto(
                $this->pdo,
                $row['ID_Producto'],
                $row['Nombre_Producto'],
                $row['Categorias_idCategorias'],
                $row['Precio_Unitario'],
                $image,
                $row['Stock']
            );
            $products[] = $product;
        }
        return $products;
    }

    public function getProductsByCategory($products) {
        $categoryNames = [
            1 => "Electrónica Destacada",
            2 => "Moda Popular",
            3 => "Hogar Esencial",
            4 => "Accesorios Tecnológicos",
            5 => "Moda de Temporada"
        ];
        $sections = [];
        foreach ($categoryNames as $catId => $catName) {
            $sections[$catName] = array_filter($products, function($product) use ($catId) {
                return $product->getCategory() == $catId;
            });
        }
        return $sections;
    }
}