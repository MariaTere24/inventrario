<?php
// actualizar_usuario.php - procesa actualización de usuario
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: usuarios.php'); exit; }

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nombre = isset($_POST['nombre']) ? $conn->real_escape_string($_POST['nombre']) : '';
$correo = isset($_POST['correo']) ? $conn->real_escape_string($_POST['correo']) : '';

if ($id <= 0) { header('Location: usuarios.php'); exit; }

$res = $conn->query("UPDATE usuarios SET nombre='".$nombre."', correo='".$correo."' WHERE id=$id");
if ($res) {
	header('Location: usuarios.php');
	exit;
} else {
	$err = $conn->error;
	header('Location: usuarios.php?error=' . urlencode($err));
	exit;
}

?>
