<?php
// conexion.php
// Compatible con LOCAL (XAMPP) y RAILWAY usando PDO

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Detectar si estamos en Railway
if (isset($_ENV['MYSQLHOST'])) {

    $DB_HOST = $_ENV['MYSQLHOST'];
    $DB_USER = $_ENV['MYSQLUSER'];
    $DB_PASS = $_ENV['MYSQLPASSWORD'];
    $DB_NAME = $_ENV['MYSQLDATABASE'];
    $DB_PORT = $_ENV['MYSQLPORT'];

} else {

    // LOCAL XAMPP
    $DB_HOST = "localhost";
    $DB_USER = "root";
    $DB_PASS = "";
    $DB_NAME = "inventario";
    $DB_PORT = 3306;

}

try {

    $conn = new PDO(
        "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8",
        $DB_USER,
        $DB_PASS
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {

    die("Error de conexión: " . $e->getMessage());

}

// Crear tabla productos
$conn->exec("CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0,
    cantidad INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Crear tabla usuarios
$conn->exec("CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    correo VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

?>