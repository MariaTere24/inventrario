<?php
// editar_producto.php - muestra formulario con datos del producto para editar
include 'conexion.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: productos.php'); exit;
}

$res = $conn->query("SELECT * FROM productos WHERE id = $id");
if (!$res || $res->num_rows == 0) {
    header('Location: productos.php'); exit;
}
$p = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Editar producto - <?php echo htmlspecialchars($p['nombre']); ?></title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <aside id="sidebar" class="sidebar">
    <div class="brand">
      <button id="btnToggle" class="hamburger">☰</button>
      <h1>Distrilay</h1>
    </div>
    <nav class="nav">
      <a href="index.php"><span class="icon">🏠</span><span class="label">Dashboard</span></a>
      <a href="productos.php" class="active"><span class="icon">📦</span><span class="label">Productos</span></a>
      <a href="usuarios.php"><span class="icon">👥</span><span class="label">Usuarios</span></a>
      <a href="informes.php"><span class="icon">📊</span><span class="label">Informes</span></a>
    </nav>
  </aside>
  <main class="main">
    <header class="main-header">
      <h2>Editar producto</h2>
    </header>
    <section class="panel">
      <form action="actualizar_producto.php" method="POST" enctype="multipart/form-data" class="form-grid">
        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
        <label>Nombre<input type="text" name="nombre" value="<?php echo htmlspecialchars($p['nombre']); ?>" required></label>
        <label>Precio<input type="number" name="precio" step="0.01" value="<?php echo $p['precio']; ?>" required></label>
        <label>Cantidad<input type="number" name="cantidad" value="<?php echo $p['cantidad']; ?>" required></label>
        <label>Imagen actual
          <?php if(!empty($p['imagen']) && file_exists(__DIR__.'/imagenes/'.$p['imagen'])): ?>
            <img src="imagenes/<?php echo htmlspecialchars($p['imagen']); ?>" class="thumb" alt="">
          <?php else: ?>
            <div class="thumb placeholder">Sin foto</div>
          <?php endif; ?>
        </label>
        <label>Subir nueva imagen<input type="file" name="imagen"></label>

        <div></div>
        <button class="btn primary" type="submit">Guardar cambios</button>
      </form>
    </section>
  </main>
  <script src="script.js"></script>
</body>
</html>
