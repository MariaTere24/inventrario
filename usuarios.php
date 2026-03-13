<?php
include 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Usuarios - Inventario Distrilay</title>
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
      <h2>Usuarios</h2>
      <p class="muted">Agregar y administrar usuarios</p>
    </header>

    <section class="panel">
      <h3>Agregar usuario</h3>
      <?php if(isset($_GET['error'])): ?>
        <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>
      <form class="form-grid" action="agregar.php" method="POST">
        <input type="hidden" name="tipo" value="usuario">
        <label>
          Nombre
          <input type="text" name="nombre" required>
        </label>

        <label>
          Correo
          <input type="email" name="correo" required>
        </label>

        <div></div>
        <button type="submit" class="btn primary">Agregar usuario</button>
      </form>
    </section>

    <section class="panel">
      <h3>Lista de usuarios</h3>
      <div class="table-wrap">
        <?php
        // PAGINACIÓN usuarios
        $perPage = 10;
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;
        $totalRes = $conn->query('SELECT COUNT(*) AS tot FROM usuarios');
        $totalRow = $totalRes->fetch_assoc();
        $totalItems = $totalRow ? (int)$totalRow['tot'] : 0;
        $totalPages = max(1, ceil($totalItems / $perPage));

        $res = $conn->query("SELECT * FROM usuarios ORDER BY id DESC LIMIT $offset, $perPage");
        ?>

        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while($u = $res->fetch_assoc()): ?>
            <tr>
              <td><?php echo $u['id']; ?></td>
              <td><?php echo htmlspecialchars($u['nombre']); ?></td>
              <td><?php echo htmlspecialchars($u['correo']); ?></td>
              <td>
                <a class="btn small view" title="Ver" href="usuarios.php?page=<?php echo $page; ?>&view=<?php echo $u['id']; ?>">👁</a>
                <a class="btn small edit" title="Editar" href="editar_usuario.php?id=<?php echo $u['id']; ?>">✏️</a>
                <a class="btn small danger" title="Eliminar" href="eliminar.php?tipo=usuario&id=<?php echo $u['id']; ?>">🗑</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

        <!-- paginador usuarios -->
        <div class="paginator">
          <?php if($page > 1): ?>
            <a class="btn" href="usuarios.php?page=<?php echo $page-1; ?>">Anterior</a>
          <?php else: ?>
            <span class="btn disabled">Anterior</span>
          <?php endif; ?>

          <?php for($i=1;$i<=$totalPages;$i++): ?>
            <?php if($i == $page): ?>
              <span class="btn primary"><?php echo $i; ?></span>
            <?php else: ?>
              <a class="btn" href="usuarios.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endif; ?>
          <?php endfor; ?>

          <?php if($page < $totalPages): ?>
            <a class="btn" href="usuarios.php?page=<?php echo $page+1; ?>">Siguiente</a>
          <?php else: ?>
            <span class="btn disabled">Siguiente</span>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <footer class="footer">
      <small>Inventario Distrilay</small>
    </footer>
  </main>

  <script src="script.js"></script>
</body>
</html>
