<?php
// conexion.php
// Compatible con LOCAL y Railway

// Detectar si estamos en Railway
if (isset($_ENV['MYSQLHOST'])) {

    // Configuración Railway
    $DB_HOST = $_ENV['MYSQLHOST'];
    $DB_USER = $_ENV['MYSQLUSER'];
    $DB_PASS = $_ENV['MYSQLPASSWORD'];
    $DB_NAME = $_ENV['MYSQLDATABASE'];
    $DB_PORT = $_ENV['MYSQLPORT'];

} else {

    // Configuración LOCAL
    $DB_HOST = 'localhost';
    $DB_USER = 'root';
    $DB_PASS = '';
    $DB_NAME = 'inventario';
    $DB_PORT = 3306;

}

// Permitir crear DB en local
$DB_CREATE = true;

// Conectar al servidor MySQL
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, '', $DB_PORT);

if ($conn->connect_error) {
    die('Error de conexión al servidor MySQL: ' . $conn->connect_error);
}

// Crear base de datos si no existe
if ($DB_CREATE) {
    $conn->query("CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
}

// Seleccionar base de datos
if (!$conn->select_db($DB_NAME)) {
    die('Base de datos "' . htmlspecialchars($DB_NAME) . '" no encontrada.');
}

// Crear tabla productos
$sqlProductos = "CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0,
    cantidad INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$conn->query($sqlProductos);

// Crear tabla usuarios
$sqlUsuarios = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    correo VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$conn->query($sqlUsuarios);

// Verificar columna imagen
$check = $conn->query("SHOW TABLES LIKE 'productos'");
if ($check && $check->num_rows > 0) {

    $col = $conn->query("SHOW COLUMNS FROM productos LIKE 'imagen'");
    if (!$col || $col->num_rows == 0) {
        $conn->query("ALTER TABLE productos ADD COLUMN imagen VARCHAR(255) DEFAULT NULL");
    }

    $col2 = $conn->query("SHOW COLUMNS FROM productos LIKE 'precio'");
    if (!$col2 || $col2->num_rows == 0) {
        $conn->query("ALTER TABLE productos ADD COLUMN precio DECIMAL(10,2) NOT NULL DEFAULT 0");
    }

    $col3 = $conn->query("SHOW COLUMNS FROM productos LIKE 'cantidad'");
    if (!$col3 || $col3->num_rows == 0) {
        $conn->query("ALTER TABLE productos ADD COLUMN cantidad INT NOT NULL DEFAULT 0");
    }
}

// Verificar columna correo
$checkU = $conn->query("SHOW TABLES LIKE 'usuarios'");
if ($checkU && $checkU->num_rows > 0) {

    $colu = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'correo'");
    if (!$colu || $colu->num_rows == 0) {
        $conn->query("ALTER TABLE usuarios ADD COLUMN correo VARCHAR(255) NOT NULL");
    }

}

// Conexión lista para usar
?>