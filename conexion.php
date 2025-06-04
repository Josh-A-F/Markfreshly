<?php
class Conexion{
    private static $host= "localhost";
    private static $dbname= "markfreshly";
    private static $username= "root";
    private static $password= "1234";

public static function conectar(){
    try {
        $conexion= new PDO("mysql:host=localhost;dbname=markfreshly", "root", "1234");
        $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $conexion;
    } catch (PDOException $e) {
        die("error en el sistema: " . $e->getMessage());
    }
}
}
?>
