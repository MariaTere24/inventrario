<?php
// editar_usuario.php - formulario para editar usuario
include 'conexion.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: usuarios.php'); exit; }

$res = $conn->query("SELECT * FROM usuarios WHERE id = $id");
if (!$res || $res->num_rows == 0){ header('Location: usuarios.php'); exit; }
$u = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Editar usuario - <?php echo htmlspecialchars($u['nombre']); ?></title>
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
      <a href="productos.php"><span class="icon">📦</span><span class="label">Productos</span></a>
      <a href="usuarios.php" class="active"><span class="icon">👥</span><span class="label">Usuarios</span></a>
      <a href="informes.php"><span class="icon">📊</span><span class="label">Informes</span></a>
    </nav>
  </aside>
  <main class="main">
    <header class="main-header">
      <h2>Editar usuario</h2>
    </header>
    <section class="panel">
      <form action="actualizar_usuario.php" method="POST" class="form-grid">
        <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
        <label>Nombre<input type="text" name="nombre" value="<?php echo htmlspecialchars($u['nombre']); ?>" required></label>
        <label>Correo<input type="email" name="correo" value="<?php echo htmlspecialchars($u['correo']); ?>" required></label>
        <div></div>
        <button class="btn primary" type="submit">Guardar cambios</button>
      </form>
    </section>
  </main>
  <script src="script.js"></script>
</body>
</html>
