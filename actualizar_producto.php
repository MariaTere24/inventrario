<?php
// actualizar_producto.php - procesa la actualización de un producto
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Location: productos.php'); exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nombre = isset($_POST['nombre']) ? $conn->real_escape_string($_POST['nombre']) : '';
$precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0;
$cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;

if ($id <= 0) { header('Location: productos.php'); exit; }

// Obtener imagen anterior
$res = $conn->query("SELECT imagen FROM productos WHERE id = $id");
$oldImage = null;
if ($res && $row = $res->fetch_assoc()) $oldImage = $row['imagen'];

$newImage = $oldImage;
if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK){
    $tmp = $_FILES['imagen']['tmp_name'];
    $orig = $_FILES['imagen']['name'];
    $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif'];
    if (in_array($ext, $allowed)){
        $newImage = uniqid('img_') . '.' . $ext;
        move_uploaded_file($tmp, __DIR__.'/imagenes/'.$newImage);
        // eliminar anterior
        if ($oldImage && file_exists(__DIR__.'/imagenes/'.$oldImage)) @unlink(__DIR__.'/imagenes/'.$oldImage);
    }
}

$imagenSql = $newImage ? "'".$conn->real_escape_string($newImage)."'" : 'NULL';

$sql = "UPDATE productos SET nombre='".$nombre."', precio=$precio, cantidad=$cantidad, imagen=$imagenSql WHERE id=$id";
$res = $conn->query($sql);
if ($res) {
    header('Location: productos.php');
    exit;
} else {
    $err = $conn->error;
    header('Location: productos.php?error=' . urlencode($err));
    exit;
}

?>
