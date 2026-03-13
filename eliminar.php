<?php

include 'conexion.php';

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'producto';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
	header('Location: index.php');
	exit;
}

if ($tipo === 'producto') {
	// Primero obtener nombre de imagen para borrarla del disco
	$res = $conn->query("SELECT imagen FROM productos WHERE id = $id");
	if ($res && $row = $res->fetch_assoc()) {
		if (!empty($row['imagen'])) {
			$path = __DIR__ . '/imagenes/' . $row['imagen'];
			if (file_exists($path)) @unlink($path);
		}
	}
	$conn->query("DELETE FROM productos WHERE id = $id");
	header('Location: productos.php');
	exit;

} elseif ($tipo === 'usuario') {
	$conn->query("DELETE FROM usuarios WHERE id = $id");
	header('Location: usuarios.php');
	exit;

} else {
	header('Location: index.php');
	exit;
}

?>