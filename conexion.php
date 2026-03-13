<?php
// conexion.php
// Conexión a MySQL. Lee configuración desde config.php si existe.

// Valores por defecto
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'inventario';
$DB_CREATE = true; // si true intentará crear la DB si no existe

// Si existe config.php, cargamos allí las variables (permite usar tu BD existente)
if (file_exists(__DIR__ . '/config.php')) {
    include __DIR__ . '/config.php';
    // config.php puede sobreescribir $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_CREATE
}

// Conectar al servidor (sin seleccionar DB) para posibles operaciones administrativas
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
if ($conn->connect_error) {
    die('Error de conexión al servidor MySQL: ' . $conn->connect_error);
}

// Crear base de datos si está permitido y no existe
if (empty($DB_CREATE)) {
    // Si $DB_CREATE es false, asumimos que el usuario ya tiene la DB creada y no hacemos CREATE DATABASE
} else {
    $conn->query("CREATE DATABASE IF NOT EXISTS `". $DB_NAME ."` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
}

// Seleccionar la base de datos (debe existir si DB_CREATE=false)
if (!$conn->select_db($DB_NAME)) {
    die('Base de datos "' . htmlspecialchars($DB_NAME) . '" no encontrada. Edita config.php para indicar tu base de datos o activa $DB_CREATE.');
}

// Crear tablas necesarias si no existen (si la base de datos existe)
$sqlProductos = "CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0,
    cantidad INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$sqlUsuarios = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    correo VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$conn->query($sqlProductos);
$conn->query($sqlUsuarios);

// Reparaciones/ajustes de esquema: añadir columnas faltantes si la tabla existe pero le faltan campos.
// Añadir columna 'imagen' a productos si falta (evita errores como "Unknown column 'imagen'")
$check = $conn->query("SHOW TABLES LIKE 'productos'");
if ($check && $check->num_rows > 0) {
    $col = $conn->query("SHOW COLUMNS FROM productos LIKE 'imagen'");
    if (!$col || $col->num_rows == 0) {
        $conn->query("ALTER TABLE productos ADD COLUMN imagen VARCHAR(255) DEFAULT NULL");
    }
    // Asegurar columnas 'precio' y 'cantidad' con tipos esperados si no existen
    $col2 = $conn->query("SHOW COLUMNS FROM productos LIKE 'precio'");
    if (!$col2 || $col2->num_rows == 0) {
        $conn->query("ALTER TABLE productos ADD COLUMN precio DECIMAL(10,2) NOT NULL DEFAULT 0");
    }
    $col3 = $conn->query("SHOW COLUMNS FROM productos LIKE 'cantidad'");
    if (!$col3 || $col3->num_rows == 0) {
        $conn->query("ALTER TABLE productos ADD COLUMN cantidad INT NOT NULL DEFAULT 0");
    }
}

// Añadir columna 'correo' a usuarios si falta
$checkU = $conn->query("SHOW TABLES LIKE 'usuarios'");
if ($checkU && $checkU->num_rows > 0) {
    $colu = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'correo'");
    if (!$colu || $colu->num_rows == 0) {
        $conn->query("ALTER TABLE usuarios ADD COLUMN correo VARCHAR(255) NOT NULL");
    }
}

// Ahora $conn está listo para usarse en otras páginas.
?>