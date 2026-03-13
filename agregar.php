<?php
// agregar.php - maneja la inserción de productos y usuarios
include 'conexion.php';

// Tipo: 'producto' o 'usuario'
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'producto';

if ($tipo === 'producto') {
	// Campos esperados: nombre, precio, cantidad, imagen
	$nombre = isset($_POST['nombre']) ? $conn->real_escape_string($_POST['nombre']) : '';
	$precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0;
	$cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;

	// Manejo de imagen
	$imagenNombre = null;
	if (!empty($_FILES['imagen'])) {
		$uploadErr = $_FILES['imagen']['error'];
		if ($uploadErr !== UPLOAD_ERR_NO_FILE) {
			if ($uploadErr !== UPLOAD_ERR_OK) {
				$phpFileUploadErrors = array(
					UPLOAD_ERR_INI_SIZE => 'El archivo excede upload_max_filesize en php.ini',
					UPLOAD_ERR_FORM_SIZE => 'El archivo excede MAX_FILE_SIZE en el formulario',
					UPLOAD_ERR_PARTIAL => 'El archivo fue subido parcialmente',
					UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo',
					UPLOAD_ERR_NO_TMP_DIR => 'Falta carpeta temporal',
					UPLOAD_ERR_CANT_WRITE => 'Fallo al escribir el archivo en el disco',
					UPLOAD_ERR_EXTENSION => 'Subida detenida por extensión'
				);
				$errMsg = isset($phpFileUploadErrors[$uploadErr]) ? $phpFileUploadErrors[$uploadErr] : 'Error de subida code ' . $uploadErr;
				header('Location: productos.php?error=' . urlencode('Error al subir imagen: ' . $errMsg));
				exit;
			}

			$tmp = $_FILES['imagen']['tmp_name'];
			$orig = $_FILES['imagen']['name'];
			$ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
			$allowed = ['jpg','jpeg','png','gif'];
			if (!in_array($ext, $allowed)) {
				header('Location: productos.php?error=' . urlencode('Tipo de archivo no permitido: ' . $ext));
				exit;
			}

			$imagenNombre = uniqid('img_') . '.' . $ext;
			$destPath = __DIR__ . '/imagenes/' . $imagenNombre;
			if (!move_uploaded_file($tmp, $destPath)) {
				header('Location: productos.php?error=' . urlencode('Error al mover el archivo subido al directorio imagenes. Ver permisos.'));
				exit;
			}
		}
	}

	$stmt = $conn->prepare("INSERT INTO productos (nombre,precio,cantidad,imagen) VALUES (?, ?, ?, ?)");
	if ($stmt) {
		// types: s (string), d (double), i (int), s (string)
		$stmt->bind_param('sdis', $nombre, $precio, $cantidad, $imagenNombre);
		$ok = $stmt->execute();
		if (!$ok) {
			// fallback to direct query
			$imagenSql = $imagenNombre ? "'".$conn->real_escape_string($imagenNombre)."'" : 'NULL';
			$ok = $conn->query("INSERT INTO productos (nombre,precio,cantidad,imagen) VALUES ('".$nombre."', $precio, $cantidad, " . $imagenSql . ")");
			$err = $conn->error;
		} else {
			$err = $stmt->error;
		}
		$stmt->close();
	} else {
		// Prepared statement failed; fallback
		$imagenSql = $imagenNombre ? "'".$conn->real_escape_string($imagenNombre)."'" : 'NULL';
		$ok = $conn->query("INSERT INTO productos (nombre,precio,cantidad,imagen) VALUES ('".$nombre."', $precio, $cantidad, " . $imagenSql . ")");
		$err = $conn->error;
	}

	if (isset($ok) && $ok) {
		header('Location: productos.php');
		exit;
	} else {
		header('Location: productos.php?error=' . urlencode($err ?? 'Error al insertar'));
		exit;
	}

} elseif ($tipo === 'usuario') {
	$nombre = isset($_POST['nombre']) ? $conn->real_escape_string($_POST['nombre']) : '';
	$correo = isset($_POST['correo']) ? $conn->real_escape_string($_POST['correo']) : '';

	$res = $conn->query("INSERT INTO usuarios (nombre, correo) VALUES ('".$nombre."', '".$correo."')");
	if ($res) {
		header('Location: usuarios.php');
		exit;
	} else {
		$err = $conn->error;
		header('Location: usuarios.php?error=' . urlencode($err));
		exit;
	}

} else {
	header('Location: index.php');
	exit;
}

?>