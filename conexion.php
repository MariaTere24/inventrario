<?php
// conexion.php
// Compatible con LOCAL (XAMPP) y RAILWAY

// Mostrar errores (solo para pruebas)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar si estamos en Railway
if (isset($_ENV['MYSQLHOST'])) {

    // CONFIGURACIÓN RAILWAY
    $DB_HOST = $_ENV['MYSQLHOST'];
    $DB_USER = $_ENV['MYSQLUSER'];
    $DB_PASS = $_ENV['MYSQLPASSWORD'];
    $DB_NAME = $_ENV['MYSQLDATABASE'];
    $DB_PORT = $_ENV['MYSQLPORT'];

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
    die("Error de conexión a la base de datos: " . $conn->connect_error);
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

?>