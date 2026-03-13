<?php
include 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Productos - Inventario Distrilay</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <aside id="sidebar" class="sidebar">
    <div class="brand">
      <button id="btnToggle" class="hamburger">☰</button>
      <h1>Distrilay</h1>
    </div>
    <nav class="nav">
      <a href="index.php"><span class="icon">🏠</span><span class="label">Manú</span></a>
      <a href="productos.php" class="active"><span class="icon">📦</span><span class="label">Productos</span></a>
      <a href="usuarios.php"><span class="icon">👥</span><span class="label">Usuarios</span></a>
      <a href="informes.php"><span class="icon">📊</span><span class="label">Informes</span></a>
    </nav>
  </aside>

  <main class="main">
    <header class="main-header">
      <h2>Productos</h2>
      <p class="muted">Agregar y administrar productos</p>
    </header>

    <section class="panel">
      <h3>Agregar producto</h3>
      <?php if(isset($_GET['error'])): ?>
        <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>
      <form class="form-grid" action="agregar.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="tipo" value="producto">
        <label>
          Nombre
          <input type="text" name="nombre" required>
        </label>

        <label>
          Precio
          <input type="number" name="precio" step="0.01" min="0" required>
        </label>

        <label>
          Cantidad
          <input type="number" name="cantidad" min="0" required>
        </label>

        <label>
          Imagen
          <input type="file" name="imagen" accept="image/*">
        </label>

        <div></div>
        <button type="submit" class="btn primary">Agregar producto</button>
      </form>
    </section>

    <section class="panel">
      <h3>Lista de productos</h3>
      <div class="table-wrap">
        <?php
        // PAGINACIÓN
        $perPage = 10;
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;
        $totalRes = $conn->query('SELECT COUNT(*) AS tot FROM productos');
        $totalRow = $totalRes->fetch_assoc();
        $totalItems = $totalRow ? (int)$totalRow['tot'] : 0;
        $totalPages = max(1, ceil($totalItems / $perPage));

        $res = $conn->query("SELECT * FROM productos ORDER BY id DESC LIMIT $offset, $perPage");
        ?>

        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Imagen</th>
              <th>Nombre</th>
              <th>Precio</th>
              <th>Cantidad</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while($p = $res->fetch_assoc()): ?>
            <tr id="product-<?php echo $p['id']; ?>">
              <td><?php echo $p['id']; ?></td>
              <td>
                <?php if(!empty($p['imagen']) && file_exists(__DIR__.'/imagenes/'.$p['imagen'])): ?>
                  <img src="imagenes/<?php echo htmlspecialchars($p['imagen']); ?>" class="thumb" alt="">
                <?php else: ?>
                  <div class="thumb placeholder">Sin foto</div>
                <?php endif; ?>
              </td>
              <td><?php echo htmlspecialchars($p['nombre']); ?></td>
              <td>$<?php echo number_format($p['precio'],2); ?></td>
              <td><?php echo (int)$p['cantidad']; ?></td>
              <td>
                <?php if(!empty($p['imagen']) && file_exists(__DIR__.'/imagenes/'.$p['imagen'])): ?>
                  <a class="btn small view" title="Ver imagen" href="imagenes/<?php echo htmlspecialchars($p['imagen']); ?>" target="_blank" rel="noopener">👁</a>
                <?php else: ?>
                  <span class="btn small disabled">👁</span>
                <?php endif; ?>
                <a class="btn small edit" title="Editar" href="editar_producto.php?id=<?php echo $p['id']; ?>">✏️</a>
                <a class="btn small danger" title="Eliminar" href="eliminar.php?tipo=producto&id=<?php echo $p['id']; ?>">🗑</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

        <!-- paginador -->
        <div class="paginator">
          <?php if($page > 1): ?>
            <a class="btn" href="productos.php?page=<?php echo $page-1; ?>">Anterior</a>
          <?php else: ?>
            <span class="btn disabled">Anterior</span>
          <?php endif; ?>

          <?php for($i=1;$i<=$totalPages;$i++): ?>
            <?php if($i == $page): ?>
              <span class="btn primary"><?php echo $i; ?></span>
            <?php else: ?>
              <a class="btn" href="productos.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endif; ?>
          <?php endfor; ?>

          <?php if($page < $totalPages): ?>
            <a class="btn" href="productos.php?page=<?php echo $page+1; ?>">Siguiente</a>
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
