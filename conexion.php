<?php
// conexion.php
// Compatible con LOCAL (XAMPP) y HOSTING (InfinityFree)

// Detectar si estamos en hosting
if ($_SERVER['SERVER_NAME'] != 'localhost') {

    // CONFIGURACIÓN HOSTING (InfinityFree)
    $DB_HOST = "sql110.infinityfree.com";
    $DB_USER = "if0_41383859";
    $DB_PASS = "0HuhdURfEE";
    $DB_NAME = "if0_41383859_inventario"; 
    $DB_PORT = 3306;

} else {

    // CONFIGURACIÓN LOCAL (XAMPP)
    $DB_HOST = "localhost";
    $DB_USER = "root";
    $DB_PASS = "";
    $DB_NAME = "inventario";
    $DB_PORT = 3306;

}

// Crear conexión
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Crear tabla productos si no existe
$sqlProductos = "CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0,
    cantidad INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$conn->query($sqlProductos);

// Crear tabla usuarios si no existe
$sqlUsuarios = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    correo VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$conn->query($sqlUsuarios);

// Conexión lista
?>